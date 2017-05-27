#!/usr/bin/env python3
import json
from collections import OrderedDict
import bs4
import sys
from common import scraper_main, get_json, format_units, url_last_path, extract_features
import re


def telekom_mobil_cartele(scraper_url: str):
    response_body = get_json(scraper_url)
    products_html = response_body['productsHtml']
    soup = bs4.BeautifulSoup(products_html, 'html5lib')

    feature_kw = OrderedDict([
        ('mobil_min_internat', 'min interna mobil'),
        ('mobil_min_nat mobil_sms_nat', 'minute sms naional'),
        ('mobil_date', 'trafic date'),
    ])

    packages = []
    extraoptiuni = soup.select('div.extraoptiuniTabWrapper')
    for extraop in extraoptiuni:
        detail_url = extraop.select_one('div.prepaidButtons a')['href']
        abon_id = url_last_path(detail_url, scraper_url)

        abon_name = extraop.select_one('div.prepaidTabTitle strong').text
        abon_price = extraop.select_one('.prepaidPrice strong').text

        features = extraop.select('.prepaidTabDescription .ptd_OfferRow')
        features = [(feature.select_one('p').text, feature.select_one('h4').text) for feature in features]
        characteristics = extract_features(features, feature_kw, abon_name)

        package = {
            'name': abon_name.strip(),
            'price': format_units(abon_price),
            'scraper_id_hint': abon_id.strip(),
            'characteristics': characteristics
        }
        packages.append(package)

    return json.dumps({"packages": packages})


if __name__ == '__main__':
    exit(scraper_main(sys.argv[1:], telekom_mobil_cartele))
