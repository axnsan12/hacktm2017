#!/usr/bin/env python3
import os, sys, inspect
currentdir = os.path.dirname(os.path.abspath(inspect.getfile(inspect.currentframe())))
parentdir = os.path.dirname(currentdir)
sys.path.insert(0, parentdir)

import json
from collections import OrderedDict
from common import scraper_main, get_soup, format_units, extract_features


def orange_abon(scraper_url: str):
    soup = get_soup(scraper_url)

    feature_kw = OrderedDict([
        ('mobil_min_nat mobil_sms_nat', 'minute sms naiona'),
        ('mobil_min_nat', 'minute naiona'),
        ('mobil_sms_nat', 'sms naiona'),
        ('mobil_min_internat', 'minute interna'),
        ('mobil_date', 'trafic internet'),
    ])

    abonamente = soup.select('.carusel-abo .item')
    packages = []
    for abon in abonamente:
        name = abon.select_one('.minbenbox.romico h3').text
        price = abon.select_one('.secbenbox .lefttext').text

        features = abon.select('.minbenbox.romico .minben')
        features = [(f.select_one('.descben').text.strip().lower(), str(f.contents[0]).strip()) for f in features]
        characteristics = extract_features(features, feature_kw, name)

        packages.append({
            'name': name.strip(),
            'price': format_units(price),
            'characteristics': characteristics
        })

    return json.dumps(packages)


if __name__ == '__main__':
    exit(scraper_main(sys.argv[1:], orange_abon))
