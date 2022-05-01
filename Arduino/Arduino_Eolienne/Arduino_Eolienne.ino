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
   ScriptName    : Arduino_Eolienne.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 25/04/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <RF24.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";

void setup() 
{
    radio.begin();                  
    radio.openWritingPipe(address); 
    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening();
}

void loop()
{  
    char data[24];
    char str_temp[6];

    double V1 = getCurrentDC();
    dtostrf(V1, 1, 2, str_temp);
    sprintf(data, "setsensor 12 %s", str_temp);
    radio.write(&data, sizeof(data));             
    delay(1);

    double V2 = ((analogRead(A0)*5.0)/1024.0)/(7500.0/(37500.0));
    dtostrf(V2, 1, 2, str_temp);
    sprintf(data, "setsensor 13 %s", str_temp);
    radio.write(&data, sizeof(data));             
    delay(1);
}

double getCurrentDC()
{
    float voltage_raw = 0;
    for(int i = 0; i < 1000; i++)
    { 
        voltage_raw += (5.0 / 1023.0)*analogRead(A1);
    }
    voltage_raw /= 1000;
    float voltage =  voltage_raw - 2.5 + 0.012;
    float current = voltage / 0.066;

    if(abs(current) > 0.05) return abs(current);
    else return 0.0;
}