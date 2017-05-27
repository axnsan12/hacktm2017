#!/usr/bin/env python3
import os,sys,inspect
currentdir = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe())))
parentdir = os.path.dirname(currentdir)
sys.path.insert(0,parentdir) 

import json
from collections import OrderedDict
import bs4
from common import scraper_main, get_json, get_soup, ScraperError
import re
from urllib.parse import urljoin, urlparse
from telekom import _format_units


def _extract_p_data(elem) -> str:
    for p in elem.select('p'):
        if p.text and not p.text.isspace():
            return p.text.strip()

    raise ScraperError(f"no text found in element {elem}")


def telekom_mobil_abonamente(scraper_url: str):
    response_body = get_json(scraper_url)
    products_html = response_body['productsHtml']
    soup = bs4.BeautifulSoup(products_html, 'html5lib')

    feature_kw = OrderedDict([
        ('mobil_min_nat', 'min internationale'),
        ('mobil_min_internat', 'min nationale'),
        ('mobil_date', 'trafic date'),
        ('mobil_sms_nat', 'sms nationale')
    ])

    packages = []
    abonamente = soup.select('div.abonamenteTabWrapper')
    for abon in abonamente:
        detail_url = abon.select_one('div.abonamenteButtons a')['href']
        if not urlparse(detail_url).netloc:
            detail_url = urljoin(scraper_url, detail_url)

        abon_name = abon.select_one('div.abonamenteTabTitle strong').text
        abon_price = abon.select_one('.abonamentePrice strong').text
        abon_id = urlparse(detail_url).path
        abon_id = os.path.basename(os.path.normpath(abon_id))

        abon_details = get_soup(detail_url)
        features = abon_details.select('#tab-1 .tarrifs-table tbody tr')
        characteristics = {}

        for feature in features:
            feature_name, feature_value = map(_extract_p_data, feature.select('td'))
            feature_words = re.sub('[^a-z]', '', feature_name.lower())
            for alias, kws in feature_kw.items():
                if all(kw in feature_words for kw in kws.split(' ')):
                    characteristics[alias] = _format_units(feature_value)
                    break

        if not all(alias in characteristics for alias in feature_kw.keys()):
            missing = set(feature_kw.keys()) - set(characteristics.keys())
            # raise ScraperError(f"{abon_name} missing values for [{', '.join(missing)}]")
            print(f"{abon_name} missing values for [{', '.join(missing)}]")

        package = {
            'name': abon_name.strip(),
            'price': _format_units(abon_price),
            'scraper_id_hint': abon_id.strip(),
            'characteristics': characteristics
        }
        packages.append(package)

    return json.dumps({"packages": packages})


if __name__ == '__main__':
    exit(scraper_main(sys.argv[1:], telekom_mobil_abonamente))
