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
   ScriptName    : Arduino_Smarthouse_Restroom.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 01/06/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>
#define RLOAD 22.0
#include "MQ135.h"

RF24 radio(9, 10);       
const byte address[6] = "14863";

unsigned long timeEplased = 0;
unsigned long timeEplasedData = 0;
MQ135 gasSensor = MQ135(A0);

void setup() 
{
    radio.begin();                  
    radio.openWritingPipe(address); 
    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening();

    pinMode(2, INPUT);
    pinMode(3, OUTPUT);
    pinMode(4, OUTPUT);
    digitalWrite(3, HIGH);
    digitalWrite(4, HIGH);
}

void loop()
{  
    char data[24];
    bool extractor = false, movement = false;
    double GazesValue = gasSensor.getPPM();

    if(GazesValue > 1000) { extractor = true; sprintf(data, "setsensor 18 %d", random(1000,1200)); if(timeEplasedData < millis()) radio.write(&data, sizeof(data)); sprintf(data, "setsensor 25 1", extractor); if(timeEplasedData < millis()) radio.write(&data, sizeof(data)); }
    else { extractor = false; sprintf(data, "setsensor 18 %d", random(600,700)); if(timeEplasedData < millis()) radio.write(&data, sizeof(data)); }

    if(timeEplased < millis())
    {
        if(extractor == 1)
        {
            digitalWrite(4, LOW);
            timeEplased = millis() + 10000;
        }
        else {
          digitalWrite(4, HIGH);
          sprintf(data, "setsensor 25 0", extractor); 
          if(timeEplasedData < millis()) radio.write(&data, sizeof(data));
        }
    }

    movement = digitalRead(2);
    if(movement == 1)
    {
        digitalWrite(3, LOW);
    }
    else digitalWrite(3, HIGH); 

    if(timeEplasedData < millis())
    {
        sprintf(data, "setsensor 26 %d", movement);
        radio.write(&data, sizeof(data));     
        timeEplasedData = millis() + 250;     
    }
}
