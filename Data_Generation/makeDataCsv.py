import urllib.request, csv, random

restaurants = "https://raw.githubusercontent.com/DataScienceSpecialization/courses/master/03_GettingData/03_02_summarizingData/data/restaurants.csv"
response = urllib.request.urlopen(restaurants)
lines = [l.decode('utf-8') for l in response.readlines()]

########################### RESTAURANTS ######################
outputList = []
first = True
currentPhoneNumbers = {}
paymentTypes = ["Cash", "Credit", "Debit", "Check", "Plus-Dollars"]
cuisineTypes = ["Italian", "Fast-Food", "Chinese", "Indian", "Mexican", "Seafood", "Barbeque", "French", "Southern", "Breakfast"]

counter = 0

with open('restaurants.csv', 'w+', encoding="utf-8") as f:
	cr = csv.reader(lines)
	f.write("r_address, r_name, r_review, r_rating, r_price, r_phone_number, opening_time, closing_time, date_opened, r_payment, r_cuisine\n")
	for row in cr:
		if first:
			first = False
			continue
		rating = "*"*random.randrange(1,6)
		review = random.randrange(0,11)
		price = "$"*random.randrange(1,4)

		phoneNumber = ""
		area_code = "(410)"
		while(True):
			secondTerm = str(random.randrange(1,889)).zfill(3)
			thirdTerm = str(random.randint(1, 9998)).zfill(4)
			phoneNumber = area_code + "-" + secondTerm + "-" + thirdTerm
			if phoneNumber not in currentPhoneNumbers:
				currentPhoneNumbers[phoneNumber] = True
				break

		h1 = random.randrange(0, 25)
		h2 = random.randrange(0, 25)

		year = random.randrange(1800, 2021)
		month = random.randrange(1, 13)
		day = random.randrange(1, 29) #Just so that I don't have to deal with February

		dateOpened = str(month) + "/" + str(day) + "/" + str(year)

		random.shuffle(paymentTypes)
		curTypes = sorted(paymentTypes[:random.randrange(1, len(paymentTypes))])
		curTypes = " ".join(curTypes)

		random.shuffle(cuisineTypes)
		cusTypes = sorted(cuisineTypes[:random.randrange(1, len(cuisineTypes))])
		cusTypes = " ".join(cusTypes)

		if h1 > h2: #r_address, r_name, r_rating, r_price, r_phone_number, closing_time, opening_time, date_opened
			outputList.append([row[5].replace("\n"," ").replace(",", ""), row[0], rating, review, price, phoneNumber, str(h2) + ":00", str(h1) + ":00", dateOpened, curTypes, cusTypes])
		else:
			outputList.append([row[5].replace("\n"," ").replace(",", ""), row[0], rating, review, price, phoneNumber, str(h1) + ":00", str(h2) + ":00", dateOpened, curTypes, cusTypes])

		counter += 1
		if counter == 100:
			break
	csvwriter = csv.writer(f, lineterminator = '\n')
	csvwriter.writerows(outputList)

######################### DRIVERS ###############################
EmplIDs = {}
driverList = []
firstnames = "https://www.nrscotland.gov.uk/files//statistics/babies-first-names-full-list/summary-records/babies-first-names-1980-1989.csv"
first = urllib.request.urlopen(firstnames)
lastnames = "https://fivethirtyeight.datasettes.com/fivethirtyeight/most-common-name%2Fsurnames.csv?_size=max"
last = urllib.request.urlopen(lastnames)

firsts = [l.decode('utf-8', 'ignore').split(',')[2].upper() for l in first.readlines()]
lasts = [l.decode('utf-8', 'ignore').split(',')[1] for l in last.readlines()]

with open('drivers.csv', 'w+', encoding='utf-8') as f:
	f.write("d_employee_ID, d_rating, d_firstname, d_lastname, d_time_worked, d_salary\n")
	for i in range(100):
		ID = ""

		while(True):
			tempID = str(random.randrange(0, 1000000000)).zfill(9)
			if tempID not in EmplIDs:
				EmplIDs[tempID] = True
				ID = tempID
				break

		rating = "*"*random.randrange(1, 6)
		firstname = firsts[random.randrange(1, len(firsts))] 
		lastname = lasts[random.randrange(1, len(lasts))]
		time_worked = random.randrange(1, 49)
		salary = random.randrange(7, 16)
		driverList.append([ID, rating, firstname, lastname, time_worked, salary])

	csvwriter = csv.writer(f, lineterminator = '\n')
	csvwriter.writerows(driverList)

############################## CUSTOMERS ###################################
Streetnames = "https://data.sfgov.org/api/views/6d9h-4u5v/rows.csv?accessType=DOWNLOAD"
streets = urllib.request.urlopen(Streetnames)
CustIDs = {}
customerList = []
with open('customers.csv', "w+", encoding='utf-8') as f:
	f.write("c_account_ID, c_address, c_firstname, c_lastname, c_phone_number, c_email\n")
	streets = [l.decode('utf-8', 'ignore').split(',')[0] for l in streets.readlines()]

	for i in range(500):
		ID = ""

		while(True):
			tempID = str(random.randrange(0, 1000000000)).zfill(9)
			if tempID not in EmplIDs and tempID not in CustIDs:
				CustIDs[tempID] = True
				ID = tempID
				break

		address = str(random.randrange(0, 10000)) + " " + streets[random.randrange(1, len(streets))] + " MD " + str(random.randrange(100000)).zfill(5)
		firstname = firsts[random.randrange(1, len(firsts))]
		lastname = lasts[random.randrange(1, len(lasts))]
		phoneNumber = ""
		while(True):
			secondTerm = str(random.randrange(1,889)).zfill(3)
			thirdTerm = str(random.randint(1, 9998)).zfill(4)
			phoneNumber = area_code + "-" + secondTerm + "-" + thirdTerm
			if phoneNumber not in currentPhoneNumbers:
				currentPhoneNumbers[phoneNumber] = True
				break
		email = firstname + "_" + lastname + "@gmail.com"
		customerList.append([ID, address, firstname, lastname, phoneNumber, email])

	csvwriter = csv.writer(f, lineterminator = '\n')
	csvwriter.writerows(customerList)

###################################################### MEALS ################################
meals = []
with open('Missouri.csv', encoding='utf-8') as f:
	for line in f:
		line = line.split(',')
		if line[0] != '':
			meals.append(line[0])

mealsOut = []
mealCounter = 0	
with open('meals.csv', 'w+', encoding='utf-8') as f:
	f.write("meal_ID, r_address, m_name, m_price\n")
	for i in outputList:
		curMeals = []
		for j in range(random.randrange(5, 16)):
			mealCounter += 1
			mName = ""
			while True:
				mName = random.choice(meals)
				if '"' in mName:
					continue
				if mName not in curMeals:
					curMeals.append(mName)
					break
			price = str(random.randrange(1, 13)) + "." + str(random.randrange(0, 100)).zfill(2)
			mealsOut.append([mealCounter, i[0].strip(), mName, "$" + price])
	csvwriter = csv.writer(f, lineterminator = '\n')
	csvwriter.writerows(mealsOut)