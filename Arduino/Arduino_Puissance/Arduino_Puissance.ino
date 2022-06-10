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
   Created       : 25/04/2022
   License       : GNU General v3.0
   Developer    : BOUELKHEIR Yassine 
*/

//#include <Filters.h> 
#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>
#include "ACS712.h"

RF24 radio(9, 10);       
const byte address1[6] = "14863";

ACS712 ACS1(A0, 5.0, 1023, 66);
ACS712 ACS2(A2, 5.0, 1023, 66);

void setup() 
{
    radio.begin();

    radio.openWritingPipe(address1);
//    radio.disableAckPayload();

    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening(); 

    for(int i = 1; i <= 8; i++)
    {
        pinMode(i, OUTPUT);
        digitalWrite(i, LOW);
    }

    ACS1.autoMidPoint();
    ACS2.autoMidPoint();
    Serial.begin(9600);
}

void loop() 
{
    char data[24];
    char str_temp[6];

    dtostrf((ACS1.mA_DC()/1000.0), 1, 2, str_temp);
    sprintf(data, "setsensor 1 %s", str_temp);
    radio.write(&data, sizeof(data));    
    Serial.println(data);                

    double TENSIONDC_VALUE = ((analogRead(A1)*5.0)/1024.0)/(7500.0/(37500.0));
    dtostrf(TENSIONDC_VALUE, 4, 2, str_temp);
    sprintf(data, "setsensor 2 %s", str_temp);
    radio.write(&data, sizeof(data));     
    Serial.println(data);               

    dtostrf((ACS2.mA_AC()/1000.0), 4, 2, str_temp);
    sprintf(data, "setsensor 3 %s", str_temp);
    radio.write(&data, sizeof(data));  
    Serial.println(data);       
      
    sprintf(data, "setsensor 4 220", str_temp);
    radio.write(&data, sizeof(data));    
    Serial.println(data);         
}
