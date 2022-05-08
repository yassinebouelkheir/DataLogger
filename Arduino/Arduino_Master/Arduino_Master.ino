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
   Created       : 30/04/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine 
*/

#include <SPI.h>
#include <nRF24L01.h>
#include <printf.h>
#include <RF24.h>
#include <RF24_config.h>

RF24 radio(9, 10);       
const byte address[6] = "14863";
const byte address2[6] = "26957";

void setup() 
{
    Serial.begin(9600);
    radio.begin();

    radio.openWritingPipe(address2);                  
    radio.openReadingPipe(1, address);
    radio.disableAckPayload();

    radio.setPALevel(RF24_PA_MAX);
    radio.stopListening(); 

    for(int i = 2; i <= 4; i++)
    {
        pinMode(i, OUTPUT);
        digitalWrite(i, LOW);
    }
    digitalWrite(2, HIGH);
}
void loop()
{   
    radio.stopListening();
    delay(10);
    char data[24];
    char str_temp[6];
             
    getChargeCommand();
    delay(1);
    
    digitalWrite(3, HIGH);
    radio.startListening();
    delay(13);
    if (radio.available()) 
    {
        char text[24];
        radio.read(&text, sizeof(text));
        Serial.println(text);
    }
    digitalWrite(3, LOW);
}
void getChargeCommand() 
{
    String Buff[10];
    int StringCount = 0;
    String data = Serial.readStringUntil('\n');

    if (data.length() > 1) {
        digitalWrite(4, HIGH);
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

        char datax[24];
        sprintf(datax, "setcharge %i %i", int(Buff[1].toInt()), bool(Buff[2].toInt()));
        radio.write(&datax, sizeof(datax));
        digitalWrite(4, LOW);
    }
}
