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
   ScriptName    : Arduino_Puissance.ino
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
const byte address1[6] = "14863";
const byte address2[6] = "26957";

void setup() 
{
   //Serial.begin(9600);
   radio.begin();
   
   radio.openWritingPipe(address1);
   radio.openReadingPipe(1, address2);
   radio.disableAckPayload();
   
   radio.setPALevel(RF24_PA_MAX); 
   radio.stopListening(); 
   
   for(int i = 1; i <= 8; i++)
   {
      pinMode(i, OUTPUT);
      digitalWrite(i, LOW);
   }
}

void loop() 
{
   char data[24];
   char str_temp[6];
   radio.startListening();
   delay(15);

   if(radio.available())
   {
      char text[32];
      radio.read(&text, sizeof(text));

      String Buff[10];
      int StringCount = 0;
      String data = String(text); 
      //Serial.println(data);
      if (data.length() > 1) {
        int id, value;
        while (data.length() > 0) {
            int index = data.indexOf(' ');
            if (index == -1) {
                Buff[StringCount++] = data;
                break;
            } else {
                Buff[StringCount++] = data.substring(0, index);
                data = data.substring(index + 1);
            }
        }
        digitalWrite(Buff[1].toInt(), bool(!Buff[2].toInt()));
      }
   }
   
   radio.stopListening();
   delay(10);
   
   double COURANTDC_VALUE = getCurrentDC();
   dtostrf(COURANTDC_VALUE, 1, 2, str_temp);
   sprintf(data, "setsensor 1 %s", str_temp);
   radio.write(&data, sizeof(data));             

   double TENSIONDC_VALUE = ((analogRead(A1)*5.0)/1024.0)/(7500.0/(37500.0));
   dtostrf(TENSIONDC_VALUE, 4, 2, str_temp);
   sprintf(data, "setsensor 2 %s", str_temp);
   radio.write(&data, sizeof(data));             

   double COURANTAC_VALUE = getCurrentAC();
   dtostrf(COURANTAC_VALUE, 4, 2, str_temp);
   sprintf(data, "setsensor 3 %s", str_temp);
   radio.write(&data, sizeof(data));             
}

double getCurrentDC()
{
   float voltage_raw = 0;
   for(int i = 0; i < 1000; i++){ 
      voltage_raw += (5.0 / 1023.0)*analogRead(A0);
   }
   voltage_raw /= 1000;
   float voltage =  voltage_raw - 2.5 + 0.012;
   float current = voltage / 0.066;

   if(abs(current) > 0.05) return abs(current);
   else return 0.0;
}

double getCurrentAC()
{
   float voltage_raw = 0;
   for(int i = 0; i < 1000; i++){ 
      voltage_raw += (5.0 / 1023.0)*analogRead(A2);
   }
   voltage_raw /= 1000;
   float voltage =  voltage_raw - 2.5 + 0.012;
   float current = voltage / 0.066;

   if(abs(current) > 0.05) return abs(current);
   else return 0.0;
}
