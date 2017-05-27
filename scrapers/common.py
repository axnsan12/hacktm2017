import argparse
import json
import traceback
from typing import Callable, Sequence
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
