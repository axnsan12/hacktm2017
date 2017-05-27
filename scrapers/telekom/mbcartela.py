#!/usr/bin/env python3
import json
from collections import OrderedDict
import bs4
import sys
from common import scraper_main, get_json
import re
from telekom import _url_last_path, _format_units


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
        abon_id = _url_last_path(detail_url, scraper_url)

        abon_name = extraop.select_one('div.prepaidTabTitle strong').text
        abon_price = extraop.select_one('.prepaidPrice strong').text

        features = extraop.select('.prepaidTabDescription .ptd_OfferRow')
        characteristics = {}

        for feature in features:
            feature_name, feature_value = feature.select_one('p').text, feature.select_one('h4').text
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
    exit(scraper_main(sys.argv[1:], telekom_mobil_cartele))
