#!/usr/bin/env python3
import json

import sys

from common import get_soup, scraper_main
from telekom import _url_last_path, _parse_metric, _format_units


def telekom_internet(scraper_url: str):
    soup = get_soup(scraper_url)

    oferte = soup.select('.oferte-container div.oferta')
    packages = []
    for oferta in oferte:
        price = oferta.select_one('.bottomContent h3.magenta').text
        name = oferta.select_one('h2').text

        detail_url = oferta.select_one('.bottomContent a')['href']
        id_hint = _url_last_path(detail_url, scraper_url)

        characteristics = {}
        for feature in oferta.select('ul li'):
            feature_text = feature.text.lower()
            channels = _parse_metric(feature_text, ['canale'])
            if channels and 'online' not in feature_text:
                characteristics['tv_nchan'] = _format_units(channels)
            elif 'inclus' in feature_text:
                characteristics['tv_rcvr'] = [True]

        package = {
            'name': name.strip(),
            'price': _format_units(price),
            'scraper_id_hint': id_hint.strip(),
            'characteristics': characteristics
        }
        packages.append(package)

    return json.dumps(packages)


if __name__ == '__main__':
    exit(scraper_main(sys.argv[1:], telekom_internet))
