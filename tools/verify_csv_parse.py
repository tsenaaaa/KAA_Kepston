import csv
import io

csv_content = '''title,totalScore,url,reviewsCount
Saka Bistro & Bar,"4,5",https://www.google.com/maps/search/?api=1&query=Saka%20Bistro%20%26%20Bar&query_place_id=ChIJ2aQ9OPPmaC4RmAkFBzxFuf0,2285
Plataran Bandung,"4,8",https://www.google.com/maps/search/?api=1&query=Plataran%20Bandung&query_place_id=ChIJybxdtwfnaC4RI9xqASBTsUk,824
House Sangkuriang Bandung,"4,5",https://www.google.com/maps/search/?api=1&query=House%20Sangkuriang%20Bandung&query_place_id=ChIJx7J8TvjmaC4RUTPQGXC9SZI,2539
RedDoorz Plus near Asia Afrika 3,"4,1",https://www.google.com/maps/search/?api=1&query=RedDoorz%20Plus%20near%20Asia%20Afrika%203&query_place_id=ChIJNcx9qCjmaC4RqY2mBrauiW0,662
Zodiak Asia Afrika Hotel Bandung,4,https://www.google.com/maps/search/?api=1&query=Zodiak%20Asia%20Afrika%20Hotel%20Bandung&query_place_id=ChIJcX25ZiTmaC4R_ASeA8NBtn4,2160
Travello Hotel,"4,4",https://www.google.com/maps/search/?api=1&query=Travello%20Hotel&query_place_id=ChIJh1CXTLjmaC4RlwtWLVhK7VA,3122
RedDoorz @ Arwiga Hotel near RS Hasan Sadikin Bandung,"4,1",https://www.google.com/maps/search/?api=1&query=RedDoorz%20%40%20Arwiga%20Hotel%20near%20RS%20Hasan%20Sadikin%20Bandung&query_place_id=ChIJc5nj413maC4RHB4VaXLnmCw,149
'''

f = io.StringIO(csv_content)
reader = csv.reader(f)
headers = next(reader)
# normalize headers
colmap = {}
for i,h in enumerate(headers):
    norm = ''.join(ch.lower() for ch in h if ch.isalnum())
    colmap[norm] = i

print('Detected headers map:', colmap)
print('\nParsed rows:')
for row in reader:
    title = row[0] if len(row) > 0 else ''
    ts = None
    rc = None
    if 'totalscore' in colmap:
        ts = row[colmap['totalscore']] if colmap['totalscore'] < len(row) else None
    if 'reviewscount' in colmap:
        rc = row[colmap['reviewscount']] if colmap['reviewscount'] < len(row) else None

    # normalize ts
    parsed_ts = None
    parsed_rc = None
    if ts is not None:
        ts_s = ts.strip()
        ts_s = ts_s.replace(',', '.')
        try:
            parsed_ts = float(ts_s)
        except:
            parsed_ts = None
    if rc is not None:
        rc_s = rc.strip()
        rc_s = ''.join(ch for ch in rc_s if ch.isdigit())
        parsed_rc = int(rc_s) if rc_s != '' else None

    print(f"- {title}: totalScore={ts!r} -> parsed={parsed_ts}, reviewsCount={rc!r} -> parsed={parsed_rc}")
