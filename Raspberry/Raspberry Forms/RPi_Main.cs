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
   ScriptName    : RPi_Main.cs
   Author        : BOUELKHEIR Yassine
   Version       : 2.0
   Created       : 01/05/2022
   License       : GNU General v3.0
   Developers    : BOUELKHEIR Yassine
*/

using System;
using System.Net;
using System.Windows.Forms;
using Color = System.Drawing.Color;

namespace RPi
{
    public partial class RPI_Main : Form
    {
        private int menuSelection = 0;
        private int browseSelection = 0;
        private int MaxSelection = 4;
        private bool isGraphEnabled = false;
        private bool isChargePanelEnabled = false;
        private MySql.Data.MySqlClient.MySqlConnection conn;

        public RPI_Main() => InitializeComponent();

        private void RPI_Main_Load(object sender, EventArgs e)
        {
            string myConnectionString = "server=localhost;uid=adminpi;pwd=adminpi;database=PFE";
            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection
                {
                    ConnectionString = myConnectionString
                };
                conn.Open();
            }
            catch (MySql.Data.MySqlClient.MySqlException)
            {
                System.Windows.Forms.Application.Exit();
            }
            conn.Close();

            if(System.Net.NetworkInformation.NetworkInterface.GetIsNetworkAvailable())
            {
                wifilabel.Visible = true;
            }
            else wifilabel.Visible = false;
            panel1.BackColor = System.Drawing.Color.FromArgb(180, 255, 255, 255);
            UpdateSelection();
        }

        private void FunctionsUpdate()
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `ID`, `PARAM`, `CONDITIONS`, `VALUE`, `RELAY`, `FNCT` FROM `FUNCTIONS` WHERE 1", conn);
            var dr = cmd.ExecuteReader();
            while (dr.Read())
            {
                int
                    id = dr.GetInt32(0),
                    paramid = dr.GetInt32(1),
                    relay = dr.GetInt32(4),
                    fnct = dr.GetInt32(5);

                double 
                    value = dr.GetFloat(3),
                    param = 0.0;

                if(paramid < 14 && (paramid != 7 && paramid != 8))
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE `ID` = "+paramid, conn);
                    var dr1 = cmd3.ExecuteReader();
                    dr1.Read();
                    param = dr1.GetFloat(0);
                    dr1.Close();
                }
                else
                {
                    switch (paramid)
                    {
                        case 7:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE `ID` = 7", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            param = ((2500 / (dr.GetFloat(0) * 0.0048828125) - 500) / 10);
                            if (param > 9999) param = 9999;
                            dr1.Close();
                            break;
                        }
                        case 8:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE `ID` = 8", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            param = ((dr1.GetFloat(0)*100)/1023);
                            dr1.Close();
                            break;
                        }
                        case 14:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE `ID` = 2", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            param = dr1.GetFloat(0);
                            dr1.Close();
                            break;
                        }
                        case 15:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `ID` FROM `SENSORS_STATIC` WHERE `ID` = (`ID` = 1 OR `ID` = 2) ORDER BY `ID` ASC", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            float courant = dr1.GetFloat(0);
                            dr1.Read();
                            param = (dr1.GetFloat(0)*courant);
                            dr1.Close();
                            break;
                        }
                        case 16:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `ID` FROM `SENSORS_STATIC` WHERE `ID` = (`ID` = 3 OR `ID` = 4) ORDER BY `ID` ASC", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            param = (dr1.GetFloat(0)*220);
                            dr1.Close();
                            break;
                        }
                        case 17:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `ID` FROM `SENSORS_STATIC` WHERE (`ID` = 12 OR `ID` = 13) ORDER BY `ID` ASC", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            float courant = dr1.GetFloat(0);
                            dr1.Read();
                            param = (dr1.GetFloat(0) * courant);
                            dr1.Close();
                            break;
                        }
                        case 18:
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd3 = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `ID` FROM `SENSORS_STATIC` WHERE `ID` = 7", conn);
                            var dr1 = cmd3.ExecuteReader();
                            dr1.Read();
                            param = ((Math.Pow((1000 - dr1.GetFloat(0)), 2) / 10) / (50));
                            dr1.Close();
                            break;
                        }
                    }
                }

                switch (dr.GetInt32(2))
                {
                    case 1:
                    {
                        if (param > value)
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd1 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = "+fnct+" WHERE `ID` = "+relay, conn);
                            cmd1.ExecuteNonQuery();
                        }
					    break;
                    }
                    case 2:
                    {
                        if (param < value)
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd1 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = "+fnct+" WHERE `ID` = "+relay, conn);
                            cmd1.ExecuteNonQuery();
                        }
					    break;
                    }
                    case 3:
                    {
                        if (param >= value)
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd1 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = "+fnct+" WHERE `ID` = "+relay, conn);
                            cmd1.ExecuteNonQuery();
                        }
					    break;
                    }
                    case 4:
                    {
                        if (param <= value)
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd1 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = "+fnct+" WHERE `ID` = "+relay, conn);
                            cmd1.ExecuteNonQuery();
                        }
					    break;
                    }
                    case 5:
                    {
                        if (param == value)
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd1 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = "+fnct+" WHERE `ID` = "+relay, conn);
                            cmd1.ExecuteNonQuery();
                        }
						break;
                    }
                    case 6:
                    {
                        if (param != value)
                        {
                            MySql.Data.MySqlClient.MySqlCommand cmd1 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = "+fnct+" WHERE `ID` = "+relay, conn);
                            cmd1.ExecuteNonQuery();
                        }
					    break;
                    }
                }
                MySql.Data.MySqlClient.MySqlCommand cmd2 = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `FUNCTIONS` SET `EXEC` = (`EXEC` + 1) WHERE `ID` = " + id, conn);
                cmd2.ExecuteNonQuery();
            }
            dr.Close();
            conn.Close();
        }

        private void Left_Btn_Click(object sender, EventArgs e)
        {
            browseSelection -= 1;
            if (isGraphEnabled) if (browseSelection < 1) browseSelection = MaxSelection;
            else if (browseSelection < 0) browseSelection = MaxSelection;
            if (isGraphEnabled) UpdateGraphSelection();
            else UpdateSelection();
        }

        private void Right_Btn_Click(object sender, EventArgs e)
        {
            browseSelection += 1;
            if (browseSelection == MaxSelection)
            {
                if (isGraphEnabled) browseSelection = 1;
                else browseSelection = 0;
            }
            if (isGraphEnabled) UpdateGraphSelection();
            else UpdateSelection();
        }

        private void UpdateSelection()
        {
            conn.Open();
            if (menuSelection == 0)
            {
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 2", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Batterie :";
                    double bat = ((dr.GetFloat(0) - 12.0) * 100 / 13.0);
                    if (bat < 0) bat = 0;

                    paramValue.Text = bat.ToString("0") + " %";
                    dr.Close();
                    Charts.Enabled = false;
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 2", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Tension DC :";
                    paramValue.Text = dr.GetFloat(0).ToString("0.0") + " V";
                    dr.Close();
                    Charts.Enabled = true;

                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 1", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Courant DC :";
                    paramValue.Text = dr.GetFloat(0).ToString("0.00") + " A";
                    dr.Close();
                    Charts.Enabled = true;
                }
                else if (browseSelection == 3)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID < 3", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    double voltage = dr.GetFloat(0);
                    dr.Read();

                    paramTitle.Text = "Puissance DC :";
                    paramValue.Text = (voltage * dr.GetFloat(0)).ToString("0") + " W";
                    dr.Close();
                    Charts.Enabled = true;
                }
            }
            else if (menuSelection == 1)
            {
                if (browseSelection == 0)
                {
                    /*MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 4", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();*/

                    paramTitle.Text = "Tension AC :";
                    //paramValue.Text = dr.GetFloat(0).ToString("0") + " V";
                    paramValue.Text = "220 V";
                    //dr.Close();
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 3", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Courant AC :";
                    paramValue.Text = dr.GetFloat(0).ToString("0.00") + " A";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 3", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    double voltage = 220; /*dr.GetFloat(0);
                    dr.Read();*/

                    paramTitle.Text = "Puissance AC :";
                    paramValue.Text = (voltage * dr.GetFloat(0)).ToString("0") + " W";
                    dr.Close();
                }
                Charts.Enabled = true;
            }
            else if (menuSelection == 2)
            {
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 12", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Tension DC :";
                    paramValue.Text = dr.GetFloat(0).ToString("0.0") + " V";
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 13", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Courant DC :";
                    paramValue.Text = dr.GetFloat(0).ToString("0.00") + " A";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 12 OR ID = 13", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    double voltage = dr.GetFloat(0);
                    dr.Read();

                    paramTitle.Text = "Puissance DC :";
                    paramValue.Text = (voltage * dr.GetFloat(0)).ToString("0") + " W";
                    dr.Close();
                }
                Charts.Enabled = true;
            }
            else if (menuSelection == 3)
            {
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 5", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "T. Ambiante :";
                    paramValue.Text = dr.GetFloat(0).ToString("0") + " °C";
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 6", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "T. Panneau :";
                    paramValue.Text = dr.GetFloat(0).ToString("0") + " °C";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 7", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Flux Lumineux :";
                    paramValue.Text = ((2500 / (dr.GetFloat(0) * 0.0048828125) - 500) / 10).ToString("0") + " LUX";
                    dr.Close();
                }
                else if (browseSelection == 3)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 7", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Irradiation :";
                    paramValue.Text = ((Math.Pow((1000 - dr.GetFloat(0)), 2) / 10) / (50)).ToString("0") + " W/m²";
                    dr.Close();
                }
                else if (browseSelection == 4)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 8", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Humidité :";
                    paramValue.Text = ((dr.GetFloat(0) * 100) / 1023).ToString("0") + " %RH";
                    dr.Close();
                }
                else if (browseSelection == 5)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 9", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "V.Vent (Aval) :";
                    paramValue.Text = dr.GetFloat(0).ToString("0") + " KM/H";
                    dr.Close();
                }
                else if (browseSelection == 6)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 10", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "V.Vent (Amon) :";
                    paramValue.Text = dr.GetFloat(0).ToString("0") + " KM/H";
                    dr.Close();
                }
                else if (browseSelection == 7)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 11", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Turbine :";
                    paramValue.Text = dr.GetFloat(0).ToString("0") + " TR/MIN";
                    dr.Close();
                }
                Charts.Enabled = true;
            }
            conn.Close();
        }
        private void UpdateGraphSelection()
        {
            conn.Open();
            if (menuSelection == 0)
            {
                if (browseSelection == 1)
                {
                    label2.Text = "Tension DC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 2 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                if (browseSelection == 2)
                {
                    label2.Text = "Courant DC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 1 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 3)
                {
                    label2.Text = "Puissance DC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 1 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();

                    cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 2 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    dr = cmd.ExecuteReader();
                    double[] vals1 = new double[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals1[i] = dr.GetFloat(0);
                    }
                    dr.Close();
                }
            }
            else if (menuSelection == 1)
            {
                if (browseSelection == 0)
                {
                    label2.Text = "Tension AC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 4 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    label2.Text = "Courant AC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 3 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    label2.Text = "Puissance AC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 4 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();

                    cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 3 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    dr = cmd.ExecuteReader();
                    double[] vals1 = new double[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals1[i] = dr.GetFloat(0);
                    }
                    dr.Close();
                }
                Charts.Enabled = true;
            }
            else if (menuSelection == 2)
            {
                if (browseSelection == 0)
                {
                    label2.Text = "Tension DC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 13 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    label2.Text = "Courant DC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 12 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    label2.Text = "Puissance DC";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 12 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();

                    cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 13 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    dr = cmd.ExecuteReader();
                    double[] vals1 = new double[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals1[i] = dr.GetFloat(0);
                    }
                    dr.Close();
                }
            }
            else if (menuSelection == 3)
            {
                label2.Text = "Température Ambiante";
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 5 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    label2.Text = "Température du Panneau";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 6 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    label2.Text = "Flux Lumineux";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 7 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = ((2500 / (dr.GetFloat(0) * 0.0048828125) - 500) / 10);
                        if (vals[i] > 9999) vals[i] = 9999;
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 3)
                {
                    label2.Text = "Irradiation";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 7 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = ((Math.Pow((1000 - dr.GetFloat(0)), 2) / 10) / (50));
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 4)
                {
                    label2.Text = "Humidité Relative";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 8 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = (dr.GetFloat(0)*100)/1023;
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 5)
                {
                    label2.Text = "Vitesse du vent (Aval)";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 9 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 6)
                {
                    label2.Text = "Vitesse du vent (Amon)";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 10 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
                else if (browseSelection == 7)
                {
                    label2.Text = "Turbine";
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE`, `UNIXDATE` FROM `SENSORS` WHERE ID = 11 ORDER BY `UNIXDATE` DESC LIMIT 5", conn);
                    var dr = cmd.ExecuteReader();

                    double[] vals = new double[5];
                    string[] dates = new string[5];
                    for (int i = 0; i < 5; i++)
                    {
                        dr.Read();
                        vals[i] = dr.GetFloat(0);
                        dates[i] = TimeSpan.FromSeconds(dr.GetInt32(1)).ToString(@"hh\:mm");
                    }
                    dr.Close();
                }
            }
            conn.Close();
        }

        private void UpdateChargeStatus()
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `CHARGES` WHERE 1 ORDER BY `ID` ASC", conn);
            var dr = cmd.ExecuteReader();
            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button1.BackColor = Color.FromArgb(255, 128, 0, 0);
                button1.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button1.BackColor = Color.FromArgb(255, 24, 155, 90);
                button1.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button2.BackColor = Color.FromArgb(255, 128, 0, 0);
                button2.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button2.BackColor = Color.FromArgb(255, 24, 155, 90);
                button2.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button3.BackColor = Color.FromArgb(255, 128, 0, 0);
                button3.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button3.BackColor = Color.FromArgb(255, 24, 155, 90);
                button3.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button4.BackColor = Color.FromArgb(255, 128, 0, 0);
                button4.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button4.BackColor = Color.FromArgb(255, 24, 155, 90);
                button4.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button5.BackColor = Color.FromArgb(255, 128, 0, 0);
                button5.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button5.BackColor = Color.FromArgb(255, 24, 155, 90);
                button5.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button6.BackColor = Color.FromArgb(255, 128, 0, 0);
                button6.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button6.BackColor = Color.FromArgb(255, 24, 155, 90);
                button6.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button7.BackColor = Color.FromArgb(255, 128, 0, 0);
                button7.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button7.BackColor = Color.FromArgb(255, 24, 155, 90);
                button7.Image = RPi.Properties.Resources.lightbulb_regular;
            }

            dr.Read();
            if (dr.GetInt32(0) == 1)
            {
                button8.BackColor = Color.FromArgb(255, 128, 0, 0);
                button8.Image = RPi.Properties.Resources.lightbulb_solid;
            }
            else
            {
                button8.BackColor = Color.FromArgb(255, 24, 155, 90);
                button8.Image = RPi.Properties.Resources.lightbulb_regular;
            }
            dr.Close();
            conn.Close();
        }

        private void Courant_Faible_Click(object sender, EventArgs e)
        {
            menuSelection = 0;
            if(!isGraphEnabled) browseSelection = 0;
            else browseSelection = 1;

            MaxSelection = 4;
            if (isGraphEnabled) UpdateGraphSelection();
            else UpdateSelection();

            Courant_Faible.BackColor = Color.FromArgb(255, 16, 103, 60);
            Courant_Fort.BackColor = Color.FromArgb(255, 24, 155, 90);
            Eolienne.BackColor = Color.FromArgb(255, 24, 155, 90);
            Meteorologie.BackColor = Color.FromArgb(255, 24, 155, 90);
            Charges.BackColor = Color.FromArgb(255, 24, 155, 90);

            if (isChargePanelEnabled)
            {
                Charts.Enabled = true;
                Left_Btn.Enabled = true;
                Right_Btn.Enabled = true;
                if (!isGraphEnabled)
                {
                    paramTitle.Visible = true;
                    paramValue.Visible = true;
                    UpdateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    UpdateGraphSelection();
                }

                label2.Visible = false;
                button1.Visible = false;
                button2.Visible = false;
                button3.Visible = false;
                button4.Visible = false;
                button5.Visible = false;
                button6.Visible = false;
                button7.Visible = false;
                button8.Visible = false;
                isChargePanelEnabled = false;
            }
            return;
        }

        private void Courant_Fort_Click(object sender, EventArgs e)
        {
            menuSelection = 1;
            browseSelection = 0;
            MaxSelection = 3;
            if (isGraphEnabled) UpdateGraphSelection();
            else UpdateSelection();

            Courant_Faible.BackColor = Color.FromArgb(255, 24, 155, 90);
            Courant_Fort.BackColor = Color.FromArgb(255, 16, 103, 60);
            Eolienne.BackColor = Color.FromArgb(255, 24, 155, 90);
            Meteorologie.BackColor = Color.FromArgb(255, 24, 155, 90);
            Charges.BackColor = Color.FromArgb(255, 24, 155, 90);

            if (isChargePanelEnabled)
            {
                Charts.Enabled = true;
                Left_Btn.Enabled = true;
                Right_Btn.Enabled = true;
                if (!isGraphEnabled)
                {
                    paramTitle.Visible = true;
                    paramValue.Visible = true;
                    UpdateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    UpdateGraphSelection();
                }

                label2.Visible = false;
                button1.Visible = false;
                button2.Visible = false;
                button3.Visible = false;
                button4.Visible = false;
                button5.Visible = false;
                button6.Visible = false;
                button7.Visible = false;
                button8.Visible = false;
                isChargePanelEnabled = false;
            }
            return;
        }

        private void Eolienne_Click(object sender, EventArgs e)
        {
            menuSelection = 2;
            browseSelection = 0;
            MaxSelection = 3;
            if (isGraphEnabled) UpdateGraphSelection();
            else UpdateSelection();

            Courant_Faible.BackColor = Color.FromArgb(255, 24, 155, 90);
            Courant_Fort.BackColor = Color.FromArgb(255, 24, 155, 90);
            Eolienne.BackColor = Color.FromArgb(255, 16, 103, 60);
            Meteorologie.BackColor = Color.FromArgb(255, 24, 155, 90);
            Charges.BackColor = Color.FromArgb(255, 24, 155, 90);

            if (isChargePanelEnabled)
            {
                Charts.Enabled = true;
                Left_Btn.Enabled = true;
                Right_Btn.Enabled = true;
                if (!isGraphEnabled)
                {
                    paramTitle.Visible = true;
                    paramValue.Visible = true;
                    UpdateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    UpdateGraphSelection();
                }

                label2.Visible = false;
                button1.Visible = false;
                button2.Visible = false;
                button3.Visible = false;
                button4.Visible = false;
                button5.Visible = false;
                button6.Visible = false;
                button7.Visible = false;
                button8.Visible = false;
                isChargePanelEnabled = false;
            }
            return;
        }

        private void Meteorologie_Click(object sender, EventArgs e)
        {
            menuSelection = 3;
            browseSelection = 0;
            MaxSelection = 8;
            if (isGraphEnabled) UpdateGraphSelection();
            else UpdateSelection();

            Courant_Faible.BackColor = Color.FromArgb(255, 24, 155, 90);
            Courant_Fort.BackColor = Color.FromArgb(255, 24, 155, 90);
            Eolienne.BackColor = Color.FromArgb(255, 24, 155, 90);
            Meteorologie.BackColor = Color.FromArgb(255, 16, 103, 60);
            Charges.BackColor = Color.FromArgb(255, 24, 155, 90);

            if (isChargePanelEnabled)
            {
                Charts.Enabled = true;
                Left_Btn.Enabled = true;
                Right_Btn.Enabled = true;
                if (!isGraphEnabled)
                {
                    paramTitle.Visible = true;
                    paramValue.Visible = true;
                    UpdateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    UpdateGraphSelection();
                }

                label2.Visible = false;
                button1.Visible = false;
                button2.Visible = false;
                button3.Visible = false;
                button4.Visible = false;
                button5.Visible = false;
                button6.Visible = false;
                button7.Visible = false;
                button8.Visible = false;
                isChargePanelEnabled = false;
            }
            return;
        }

        private void UpdateParams_Tick(object sender, EventArgs e)
        {
            string date = DateTime.Now.ToString("dd/MM/yyyy hh:mm");
            timelabel.Text = date;

            if (System.Net.NetworkInformation.NetworkInterface.GetIsNetworkAvailable())
            {
                if (isChargePanelEnabled) UpdateChargeStatus();
                else if (!isGraphEnabled) UpdateSelection();
                FunctionsUpdate();
                wifilabel.Visible = true;
            }
            else wifilabel.Visible = false;
            return;
        }

        private void Charts_Click(object sender, EventArgs e)
        {
            if (!isGraphEnabled)
            {
                isGraphEnabled = true;
                Charts.Text = "Passer en mode numérique";
                paramTitle.Visible = false;
                paramValue.Visible = false;
                label2.Text = paramTitle.Text.Replace(":", "");
                label2.Visible = true;
                UpdateGraphSelection();
            }
            else
            {
                isGraphEnabled = false;
                Charts.Text = "Passer en mode graphique";
                paramTitle.Visible = true;
                paramValue.Visible = true;
                label2.Text = "Panneau de contrôle des charges";
                label2.Visible = false;
                UpdateSelection();
            }
        }

        private void Charges_Click(object sender, EventArgs e)
        {
            if (!isChargePanelEnabled)
            {
                Charts.Enabled = false;
                //if (isGraphEnabled) ;
                Charts.Text = "Passer en mode graphique";
                isGraphEnabled = false;
                Left_Btn.Enabled = false;
                Right_Btn.Enabled = false;
                paramTitle.Visible = false;
                paramValue.Visible = false;

                Courant_Faible.BackColor = Color.FromArgb(255, 24, 155, 90);
                Courant_Fort.BackColor = Color.FromArgb(255, 24, 155, 90);
                Eolienne.BackColor = Color.FromArgb(255, 24, 155, 90);
                Meteorologie.BackColor = Color.FromArgb(255, 24, 155, 90);
                Charges.BackColor = Color.FromArgb(255, 16, 103, 60);

                label2.Text = "Panneau de contrôle des charges";
                label2.Visible = true;
                button1.Visible = true;
                button2.Visible = true;
                button3.Visible = true;
                button4.Visible = true;
                button5.Visible = true;
                button6.Visible = true;
                button7.Visible = true;
                button8.Visible = true;

                UpdateChargeStatus();
                isChargePanelEnabled = true;
            }
        }

        private void Button1_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 1", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(1);
        }

        private void Button2_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 2", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(2);
        }

        private void Button3_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 3", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(3);
        }

        private void Button4_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 4", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(4);
        }

        private void Button5_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 5", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(5);
        }

        private void Button6_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 6", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(6);
        }

        private void Button7_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 7", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(7);
        }

        private void Button8_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 8", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            LogChargeChanges(8);
        }

        private void UpdateCharts_Tick(object sender, EventArgs e)
        {
            if(isGraphEnabled) UpdateGraphSelection();
            return;
        }
        private void LogChargeChanges(int id)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `CHARGES` WHERE ID = "+id+" LIMIT 1", conn);
            var dr = cmd.ExecuteReader();
            dr.Read();
            int newstate = dr.GetInt32(0);
            dr.Close();

            cmd = new MySql.Data.MySqlClient.MySqlCommand("INSERT INTO `HISTORY` (`USERNAME`, `IP`, `TYPE`, `VALUE`) VALUES ('RaspberryPi', '127.0.0.1', 1, 'Mettre à jour de l état de charge IN"+id+" à "+newstate+"')", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
            return;
        }
    }
}