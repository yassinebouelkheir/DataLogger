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
   ScriptName    : Arduino_Smarthouse_AC.ino
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 01/06/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

#include <SPI.h>
#include <printf.h>

void setup() 
{
    pinMode(2, OUTPUT);
    pinMode(3, OUTPUT);
    
    pinMode(4, OUTPUT);
    pinMode(5, OUTPUT);

    pinMode(6, INPUT);
    pinMode(7, INPUT);
    pinMode(8, INPUT);

    digitalWrite(2, LOW);
    digitalWrite(3, LOW);
    
    digitalWrite(4, HIGH);
    analogWrite(5, 127);
}
void loop()
{   
    if(digitalRead(6) && !digitalRead(7)) // AC
    {
        digitalWrite(2, HIGH);
        digitalWrite(3, LOW); 
    }
    else if(!digitalRead(6) && digitalRead(7)) // FAN
    {
        digitalWrite(2, LOW);
        digitalWrite(3, HIGH); 
    }
    else if(digitalRead(6) && digitalRead(7)) // BOTH
    {
        digitalWrite(2, HIGH);
        digitalWrite(3, HIGH); 
    }
    else // NOTHING
    {
        digitalWrite(2, LOW);
        digitalWrite(3, LOW);
    }

    if(digitalRead(8)) openWindow();
    else closeWindow();
}

void openWindow()
{
    return;
}

void closeWindow()
{
    return;
}