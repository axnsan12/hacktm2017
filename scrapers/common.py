import argparse
import json
import os
import re
import traceback
from collections import OrderedDict
from typing import Callable, Sequence, Tuple, List, Dict
from urllib.parse import urlparse, urljoin

import bs4
import requests


class ScraperError(Exception):
    pass


def scraper_main(args: Sequence[any], scraper_func: Callable[[str], int]) -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("scrape_url", help="url to scrape from")
    parser.add_argument("output_filename", help="path to output JSON file that will be populated with new packages")
    args = parser.parse_args(args)

    url, outfile = args.scrape_url, args.output_filename
    try:
        output = scraper_func(url)
        with open(outfile, 'wt') as f:
            f.write(output)

        return 0
    except Exception as e:
        write_error(outfile, str(e) + '\n\n' + ''.join(traceback.format_tb(e.__traceback__)))
        return 1


def write_error(output_filename: str, error_message: str) -> int:
    with open(output_filename, 'wt') as f:
        f.write(error_message)

    print(error_message)
    return 1


def get_soup(url: str) -> bs4.BeautifulSoup:
    response = requests.get(url)
    if response.status_code != 200:
        raise ScraperError(f'Request to {url} failed with status code {response.status_code}')

    soup = bs4.BeautifulSoup(response.text, 'html5lib')
    return soup


def get_json(url: str) -> dict:
    response = requests.get(url)
    if response.status_code != 200:
        raise ScraperError(f'Request to {url} failed with status code {response.status_code}')

    return json.loads(response.text)


def format_units(value: str, ):
    return [e for e in value.strip().replace(',', '.').split() if e]


def parse_metric(feature_text, units):
    words = re.split('[^a-z0-9]', feature_text.lower())
    for unit in units:
        if unit.lower() in words:
            unit_token = words.index(unit.lower())
            value = int(words[unit_token - 1])
            return f"{value} {unit}"

    return ''


def url_last_path(url: str, root_url: str) -> str:
    if not urlparse(url).netloc:
        url = urljoin(root_url, url)

    last = urlparse(url).path
    last = os.path.basename(os.path.normpath(last))
    return last


def extract_features(features_raw: List[Tuple[str, str]], feature_kw: Dict[Tuple[str, str], str], name: str = None):
    features = {}
    for feature_text, feature_value in features_raw:
        feature_words = re.sub('[^a-z]', '', feature_text.lower())
        for alias, kws in feature_kw.items():
            if all(kw in feature_words for kw in kws.split(' ')):
                features[alias] = format_units(feature_value)

    if not all(alias in features for alias in feature_kw.keys()):
        missing = set(feature_kw.keys()) - set(features.keys())
        # raise ScraperError(f"{abon_name} missing values for [{', '.join(missing)}]")
        print(f"{name} missing values for [{', '.join(missing)}]")

    return features
