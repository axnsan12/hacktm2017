import argparse
from typing import Callable, \
    Sequence


def scraper_main(args: Sequence[any], scraper_func: Callable[[str, str, str], int]) -> int:
    parser = argparse.ArgumentParser()
    parser.add_argument("scrape_url", help="url to scrape from")
    parser.add_argument("input_filename", help="path to file containing JSON of existing packages")
    parser.add_argument("output_filename", help="path to output JSON file that will be populated with new packages")
    args = parser.parse_args(args)

    url, infile, outfile = args.scrape_url, args.input_filename, args.output_filename
    return scraper_func(url, infile, outfile)
