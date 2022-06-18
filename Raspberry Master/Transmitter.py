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
#    ScriptName    : Transmitter.py
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
arduino = serial.Serial("/dev/ttyACM1", 9600, timeout=1)

rowcounts = 1

def transmitterHandler():
	global rowcounts
	print('transmitterHandler Running. Press CTRL-C to exit.')
	time.sleep(0.1) 
	if arduino.isOpen():
		print("{} connected!".format(arduino.port))
		time.sleep(5)
		try:
			while True:
				cursor = db.cursor(buffered=True)
				cursor.execute("SELECT VALUE FROM `CHARGES` WHERE `ID` =" + str(rowcounts) + " LIMIT 1")
				db.commit()
				result = cursor.fetchall()
				for row in result:
					arduino.write(str.encode("setcharge " + str(rowcounts) + " " +  str(row[0]) + "\n"))
					print("R: setcharge " + str(rowcounts) + " " +  str(row[0]))
				rowcounts += 1
				if rowcounts == 5: 
					rowcounts = 1
					time.sleep(0.3)

		except KeyboardInterrupt:
			print("KeyboardInterrupt has been caught.")

if __name__ == "__main__":
	
	GPIO.setwarnings(False)

	reciever = threading.Thread(target=transmitterHandler)
	reciever.start()

	print("Data Logger v2.0 python script - PFE 2021/2022");

	reciever.join()
