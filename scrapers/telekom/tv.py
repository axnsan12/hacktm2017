#!/usr/bin/env python3
import os, sys, inspect
currentdir = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe())))
parentdir = os.path.dirname(currentdir)
sys.path.insert(0, parentdir)

import json
from common import get_soup, scraper_main, format_units, parse_metric, url_last_path


def telekom_internet(scraper_url: str):
    soup = get_soup(scraper_url)

    oferte = soup.select('.oferte-container div.oferta')
    packages = []
    for oferta in oferte:
        price = oferta.select_one('.bottomContent h3.magenta').text
        name = oferta.select_one('h2').text

        detail_url = oferta.select_one('.bottomContent a')['href']
        id_hint = url_last_path(detail_url, scraper_url)

        characteristics = {}
        for feature in oferta.select('ul li'):
            feature_text = feature.text.lower()
            channels = parse_metric(feature_text, ['canale'])
            if channels and 'online' not in feature_text:
                characteristics['tv_nchan'] = format_units(channels)
            elif 'inclus' in feature_text:
                characteristics['tv_rcvr'] = [True]

        package = {
            'name': name.strip(),
            'price': format_units(price),
            'scraper_id_hint': id_hint.strip(),
            'characteristics': characteristics
        }
        packages.append(package)

    return json.dumps({"packages": packages})


if __name__ == '__main__':
    exit(scraper_main(sys.argv[1:], telekom_internet))
