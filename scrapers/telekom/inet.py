#!/usr/bin/env python3
import json

import sys

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
            speed = parse_metric(feature_text, ['Mbps', 'Gbps'])
            if speed:
                characteristics['inet_down'] = characteristics['inet_up'] = format_units(speed)
            elif 'inclus' in feature_text:
                characteristics['inet_router'] = True

        package = {
            'name': name.strip(),
            'price': format_units(price),
            'scraper_id_hint': id_hint.strip(),
            'characteristics': characteristics
        }
        packages.append(package)

    return json.dumps(packages)


if __name__ == '__main__':
    exit(scraper_main(sys.argv[1:], telekom_internet))
