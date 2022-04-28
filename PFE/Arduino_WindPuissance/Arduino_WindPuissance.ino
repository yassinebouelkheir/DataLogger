/**
   Copyright (c) 2022 Data Logger

   This program is free software: you can redistribute it and/or modify it under the terms of the
   GNU General Public License as published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
   even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
   General Public License for more details.

   You should have received a copy of the GNU General Public License along with this program.
   If not, see <http://www.gnu.org/licenses/>.
*/

/*
   ScriptName    : Arduino_WindPuissance.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 18/03/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine, CHENAFI Soumia
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";

void setup() 
{
   Serial.begin(9600);
   radio.begin();                  
   radio.openWritingPipe(address); 
   radio.setPALevel(RF24_PA_MAX); 
   radio.stopListening();          
}

void loop()
{  
   char data[24];
   char str_temp[6];


   double COURANTDC_VALUE = 0.00;
   dtostrf(COURANTDC_VALUE, 1, 2, str_temp);
   sprintf(data, "setsensor 1 %s", str_temp);
   radio.write(&data, sizeof(data));             
   delay(1);


   double TENSIONDC_VALUE = ((analogRead(A1)*5.0)/1024.0)/(7500.0/(37500.0));
   dtostrf(TENSIONDC_VALUE, 4, 2, str_temp);
   sprintf(data, "setsensor 2 %s", str_temp);
   radio.write(&data, sizeof(data));             
   delay(1);


   double COURANTAC_VALUE = 0.00;
   dtostrf(COURANTAC_VALUE, 4, 2, str_temp);
   sprintf(data, "setsensor 3 %s", str_temp);
   radio.write(&data, sizeof(data));             
   delay(1);


   double TENSIONAC_VALUE = 0.00;
   dtostrf(TENSIONAC_VALUE, 4, 2, str_temp);
   sprintf(data, "setsensor 4 %s", str_temp);
   radio.write(&data, sizeof(data));             
   delay(1);
}