import urllib.request, csv, random

restaurants = "https://raw.githubusercontent.com/DataScienceSpecialization/courses/master/03_GettingData/03_02_summarizingData/data/restaurants.csv"
response = urllib.request.urlopen(restaurants)

outputList = []
first = True
with open('restaurants.csv', 'w+', encoding="utf-8") as f:
	lines = [l.decode('utf-8') for l in response.readlines()]
	cr = csv.reader(lines)
	for row in cr:
		if first:
			first = False
			continue
		rating = "*"*random.randrange(1,6)
		review = random.randrange(0,11)
		price = "$"*random.randrange(1,4)

		area_code = "(410)"
		secondTerm = str(random.randrange(1,889)).zfill(3)
		thirdTerm = str(random.randint(1, 9998)).zfill(4)
		phoneNumber = area_code + "-" + secondTerm + "-" + thirdTerm

		h1 = random.randrange(0, 25)
		h2 = random.randrange(0, 25)

		year = random.randrange(1800, 2021)
		month = random.randrange(1, 13)
		day = random.randrange(1, 29) #Just so that I don't have to deal with February

		dateOpened = str(month) + "/" + str(day) + "/" + str(year)

		if h1 > h2: #r_address, r_name, r_rating, r_price, r_phone_number, closing_time, opening_time, date_opened
			outputList.append([row[5].replace("\n"," "), row[0], rating, review, price, phoneNumber, str(h2) + ":00", str(h1) + ":00", dateOpened])
		else:
			outputList.append([row[5].replace("\n",""), row[0], rating, review, price, phoneNumber, str(h1) + ":00", str(h2) + ":00", dateOpened])

	csvwriter = csv.writer(f, lineterminator = '\n')
	csvwriter.writerows(outputList)