##
 # Copyright (c) 2022 Data Logger
 #
 # This program is free software: you can redistribute it and/or modify it under the terms of the
 # GNU General Public License as published by the Free Software Foundation, either version 3 of the
 # License, or (at your option) any later version.
 #
 # This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 # even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 # General Public License for more details.
 #
 # You should have received a copy of the GNU General Public License along with this program.
 # If not, see <http://www.gnu.org/licenses/>.
##

## 
#    ScriptName    : Reciever.py
#    Author        : BOUELKHEIR Yassine
#    Version       : 2.0
#    Created       : 03/06/2022
#    License       : GNU General v3.0
#    Developers    : BOUELKHEIR Yassine 
##

import RPi.GPIO as GPIO
import mysql.connector
import serial
import time
import threading
import os

db = mysql.connector.connect(host="localhost", user="adminpi", password="adminpi", database='PFE') 
arduino = serial.Serial("/dev/ttyACM0", 9600, timeout=1)

lastquerytime = [1.0,1.0,1.0,1.0,1.0,1.0,1.0,1.0]
dcCount = 0
acCount = 0
tempCount = 0
windSpeedCount = 0
eoCount = 0

def getquerytime(x, y=0):
	global lastquerytime
	global tempCount
	if y == 0:
		if x == '1' : #DC
			return lastquerytime[0]
		elif x == '2': # DC
			return lastquerytime[0]
		elif x == '3': # AC
			return lastquerytime[1]
		elif x == '4': # AC
			return lastquerytime[1]
		elif x == '5': # Temp 1
			return lastquerytime[2]
		elif x == '6': # Temp 2
			return lastquerytime[2]
		elif x == '7': # Brightness
			return lastquerytime[3]
		elif x == '8': # Humidity
			return lastquerytime[4]
		elif x == '9': # Wind Speed 1
			return lastquerytime[5]
		elif x == '10': # Wind Speed 2
			return lastquerytime[5]
		elif x == '11': # Turbine
			return lastquerytime[5]
		elif x == '12': # Eo Tension DC
			return lastquerytime[6]
		elif x == '13': # Eo Courant DC
			return lastquerytime[6]
		elif int(x) > 13: # Smart House
			return lastquerytime[7]

	else:
		if x == '1' : # DC
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 1 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if dcCount == 1:
					lastquerytime[0] = time.time() + row[0]*60
					dcCount = 0
					break;
				elif dcCount < 1:
					dcCount += 1
		elif x == '2': # DC
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 1 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if dcCount == 1:
					lastquerytime[0] = time.time() + row[0]*60
					dcCount = 0
					break;
				elif dcCount < 1:
					dcCount += 1
		elif x == '3': # AC
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 2 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if acCount == 1:
					lastquerytime[1] = time.time() + row[0]*60
					acCount = 0
					break;
				elif acCount < 1:
					acCount += 1
		elif x == '4': # AC
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 2 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if acCount == 1:
					lastquerytime[1] = time.time() + row[0]*60
					acCount = 0
					break;
				elif acCount < 1:
					acCount += 1
		elif x == '5': # Temp 1
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 3 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if tempCount == 1:
					lastquerytime[2] = time.time() + row[0]*60
					tempCount = 0
					break;
				elif tempCount < 1:
					tempCount += 1
					
		elif x == '6': # Temp 2
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 3 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if tempCount == 1:
					lastquerytime[2] = time.time() + row[0]*60
					tempCount = 0
					break;
				elif tempCount < 1:
					tempCount += 1
		elif x == '7': # Brightness
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 4 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				lastquerytime[3] = time.time() + row[0]*60
				break;
		elif x == '8': # Humidity
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 5 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				lastquerytime[4] = time.time() + row[0]*60
				break;
		elif x == '9': # Wind Speed 1
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 6 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if windSpeedCount == 2:
					lastquerytime[5] = time.time() + row[0]*60
					windSpeedCount = 0
					break;
				elif windSpeedCount < 2:
					windSpeedCount += 1
		elif x == '10': # Wind Speed 2
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 6 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if windSpeedCount == 2:
					lastquerytime[5] = time.time() + row[0]*60
					windSpeedCount = 0
					break;
				elif windSpeedCount < 2:
					windSpeedCount += 1
		elif x == '11': # Turbine
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 6 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if windSpeedCount == 2:
					lastquerytime[5] = time.time() + row[0]*60
					windSpeedCount = 0
					break;
				elif windSpeedCount < 2:
					windSpeedCount += 1
		elif x == '12': # EO Tension DC
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 7 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if eoCount == 1:
					lastquerytime[6] = time.time() + row[0]*60
					eoCount = 0
					break;
				elif eoCount < 1:
					eoCount += 1
		elif x == '13': # EO Courant DC
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 7 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				if eoCount == 1:
					lastquerytime[6] = time.time() + row[0]*60
					eoCount = 0
					break;
				elif eoCount < 1:
					eoCount += 1
		elif x == '18': # Restroom gauzes level
			cursor = db.cursor(buffered=True)
			cursor.execute("SELECT time FROM `UPDATETIME` WHERE ID = 8 LIMIT 1")
			db.commit()
			result = cursor.fetchall()
			for row in result:
				lastquerytime[7] = time.time() + row[0]*60
		return 1;

def receiverHandler():
	global lastquerytime
	print('recieverHandler Running. Press CTRL-C to exit.')
	time.sleep(0.1) 
	if arduino.isOpen():
		print("{} connected!".format(arduino.port))
		time.sleep(5)
		try:
			while True:
				#arduino.write(str.encode("OK"))
				while arduino.inWaiting()==0: pass
				if  arduino.inWaiting()>0: 
					answer=arduino.readline()
					decodedanswer = answer.decode(errors='ignore').replace('\n', '')
					print("A: " + decodedanswer)
					time.sleep(0.02)
					datasplitted = decodedanswer.split(' ')
					
					if datasplitted[0] == 'setsensor':
						if (type(datasplitted[1]) == int or float) and (type(datasplitted[2]) == int or float):
							if time.time() < getquerytime(datasplitted[1]):
								cursor = db.cursor(buffered=True)
								try: 
									cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
								except:
									pass
								db.commit()

							else:
								cursor = db.cursor(buffered=True)
								try: 
									cursor.execute("UPDATE `SENSORS_STATIC` SET VALUE = "+ str(datasplitted[2]) +" WHERE ID = " + str(datasplitted[1]))
								except:
									pass
								db.commit()
								time.sleep(0.01)		
								cursor = db.cursor(buffered=True)
								try:
									cursor.execute("INSERT INTO `SENSORS` (ID, VALUE, UNIXDATE) VALUES ("+ str(datasplitted[1]) +", " + str(datasplitted[2]) +", " + str(time.time()) + ")")
								except:
									pass
								db.commit()
								getquerytime(datasplitted[1], 1)
								time.sleep(0.01)						
		except KeyboardInterrupt:
			print("KeyboardInterrupt has been caught.")

if __name__ == "__main__":
	
	GPIO.setmode(GPIO.BCM)
	GPIO.setwarnings(False)
	GPIO.setup(21, GPIO.OUT)
	GPIO.output(21, GPIO.LOW)

	reciever = threading.Thread(target=receiverHandler)
	reciever.start()

	print("Data Logger v2.0 python script - PFE 2021/2022");
	GPIO.output(21, GPIO.HIGH)

	reciever.join()
