import os
import re
from typing import Tuple
from urllib.parse import urlparse, urljoin


def _url_last_path(url: str, root_url: str) -> str:
    if not urlparse(url).netloc:
        url = urljoin(root_url, url)

    last = urlparse(url).path
    last = os.path.basename(os.path.normpath(last))
    return last


def _parse_metric(feature_text, units):
    words = re.split('[^a-z0-9]', feature_text.lower())
    for unit in units:
        if unit.lower() in words:
            unit_token = words.index(unit.lower())
            value = int(words[unit_token - 1])
            return f"{value} {unit}"

    return ''


def _format_units(value: str, ):
    return [e for e in value.strip().replace(',', '.').split() if e]
