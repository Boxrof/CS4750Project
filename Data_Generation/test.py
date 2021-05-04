with open('ownersToRestaurants.csv', 'w+') as r:
	with open('owners.csv') as f:
		x = f.readlines()
		with open('restaurants.csv', encoding='utf-8') as g:
			y = g.readlines()
			for i,j in zip(x,y):
				r_id = j.split(',')[0]
				user_id = i.split(',')[0]
				r.write(user_id + "," + r_id + '\n')