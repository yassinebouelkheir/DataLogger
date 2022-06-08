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
   ScriptName    : Arduino_Relay_Module.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 06/06/2022
   License       : GNU General v3.0
   Developer    : BOUELKHEIR Yassine 
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>

RF24 radio(9, 10);       
const byte address1[6] = "26957";

void setup() 
{
    radio.begin();

    radio.openReadingPipe(1, address1);
    radio.disableAckPayload();

    radio.setPALevel(RF24_PA_MAX); 
    radio.startListening(); 

    Serial.begin(9600);
    pinMode(2, OUTPUT);
    pinMode(3, OUTPUT);
    pinMode(4, OUTPUT);
    pinMode(5, OUTPUT);

    digitalWrite(2, HIGH);
    digitalWrite(3, HIGH);
    digitalWrite(4, HIGH);
    digitalWrite(5, HIGH);
}

void loop() 
{
    if(radio.available())
    {
        char text[32];
        radio.read(&text, sizeof(text));

        String Buff[10];
        int StringCount = 0;
        String data = String(text); 
        if (data.length() > 1) 
        {
            int id, value;
            while (data.length() > 0) 
            {
                int index = data.indexOf(' ');
                if (index == -1) 
                {
                    Buff[StringCount++] = data;
                    break;
                } 
                else 
                {
                    Buff[StringCount++] = data.substring(0, index);
                    data = data.substring(index + 1);
                }
            }
            digitalWrite(Buff[1].toInt()+1, bool(!Buff[2].toInt()));
        }
    }
}
