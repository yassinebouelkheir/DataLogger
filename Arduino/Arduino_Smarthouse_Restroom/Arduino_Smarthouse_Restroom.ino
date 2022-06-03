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
   ScriptName    : Smarthouse_Restroom.ino
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

RF24 radio(9, 10);       
const byte address[6] = "57369";

unsigned long timeEplased;
unsigned long timeEplased1;

void setup() 
{
    radio.begin();                  
    radio.openWritingPipe(address); 
    radio.setPALevel(RF24_PA_MAX); 
    radio.stopListening();

    pinMode(5, INPUT);
    pinMode(6, INPUT);

    pinMode(7, OUTPUT);
    pinMode(8, OUTPUT);
    timeEplased = millis()-3000;
    timeEplased1 = millis()-500;
}

void loop()
{  
    char data[24];
    bool extractor = false, movement = false;
    double GazesValue;

    if(GazesValue > 600) extractor = true;
    else extractor = false;

    if(((millis() + 3000) < timeEplased))
    {
        if(extractor == 1)
        {
            digitalWrite(7, HIGH);
            timeEplased1 = millis();
        }
        else digitalWrite(7, LOW);
    }

    movement = digitalRead(6);
    if(movement == 1)
    {
        digitalWrite(8, HIGH);
    }
    else digitalWrite(8, LOW);

    if(((millis() + 500) < timeEplased1))
    {
        sprintf(data, "setsensor 16 %d", GazesValue);
        radio.write(&data, sizeof(data));

        sprintf(data, "setsensor 23 %d", extractor);
        radio.write(&data, sizeof(data));   

        sprintf(data, "setsensor 24 %d", movement);
        radio.write(&data, sizeof(data));      
        timeEplased1 = millis();    
    }
}


