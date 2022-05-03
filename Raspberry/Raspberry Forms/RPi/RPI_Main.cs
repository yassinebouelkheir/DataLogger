using MySql.Data.MySqlClient;
using LiveCharts; 
using LiveCharts.Wpf; 
using LiveCharts.WinForms;

namespace RPi
{
    public partial class RPI_Main : Form
    {
        private int menuSelection = 0;
        private int browseSelection = 0;
        private int MaxSelection = 4;
        private bool isGraphEnabled = false;
        private bool isChargePanelEnabled = false;
        MySql.Data.MySqlClient.MySqlConnection conn;

        #pragma warning disable CS8618
        public RPI_Main() => InitializeComponent();

        private void RPI_Main_Load(object sender, EventArgs e)
        {
            string myConnectionString = "server=localhost;uid=root;pwd=;database=PFE";

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();
            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                MessageBox.Show(ex.Message, "ERROR: CONNECTION NOT POSSIBLE", MessageBoxButtons.OK);
                this.Close();
            }
            conn.Close();
            panel1.BackColor = Color.FromArgb(150, 255, 255, 255);
            updateSelection();
        }

        private void Left_Btn_Click(object sender, EventArgs e)
        {
            browseSelection -= 1;
            if (browseSelection < 0) browseSelection = MaxSelection;
            updateSelection();
        }

        private void Right_Btn_Click(object sender, EventArgs e)
        {
            browseSelection += 1;
            if (browseSelection == MaxSelection) browseSelection = 0;
            updateSelection();
        }

        private void updateSelection()
        {
            conn.Open();
            if (menuSelection == 0)
            {
                if(browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 1", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Batterie :";
                    double bat = ((dr.GetFloat(0) - 12.0)*100/13.0);
                    if (bat < 0) bat = 0;

                    paramValue.Text = bat + " %";
                    dr.Close();
                }
                else if(browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 1", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Tension DC :";
                    paramValue.Text = dr.GetFloat(0) + " V";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 2", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Courant DC :";
                    paramValue.Text = dr.GetFloat(0) + " A";
                    dr.Close();
                }
                else if (browseSelection == 3)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID < 3", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    double voltage = dr.GetFloat(0);
                    dr.Read();

                    paramTitle.Text = "Puissance DC :";
                    paramValue.Text = voltage * dr.GetFloat(0) +  " W";
                    dr.Close();
                }
            }
            else if (menuSelection == 1)
            {
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 4", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Tension AC :";
                    paramValue.Text = dr.GetFloat(0) + " V";
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 3", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Courant AC :";
                    paramValue.Text = dr.GetFloat(0) + " A";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 3 OR ID = 4", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    double voltage = dr.GetFloat(0);
                    dr.Read();

                    paramTitle.Text = "Puissance AC :";
                    paramValue.Text = voltage * dr.GetFloat(0) + " W";
                    dr.Close();
                }
            }
            else if (menuSelection == 2)
            {
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 12", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Tension AC :";
                    paramValue.Text = dr.GetFloat(0) + " V";
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 13", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Courant AC :";
                    paramValue.Text = dr.GetFloat(0) + " A";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 12 OR ID = 13", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    double voltage = dr.GetFloat(0);
                    dr.Read();

                    paramTitle.Text = "Puissance AC :";
                    paramValue.Text = voltage * dr.GetFloat(0) + " W";
                    dr.Close();
                }
            }
            else if (menuSelection == 3)
            {
                if (browseSelection == 0)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 5", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Temp. Ambiant :";
                    paramValue.Text = dr.GetFloat(0) + " °C";
                    dr.Close();
                }
                else if (browseSelection == 1)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 6", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Temp. Panneau :";
                    paramValue.Text = dr.GetFloat(0) + " °C";
                    dr.Close();
                }
                else if (browseSelection == 2)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 7", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "Flux Lumineux :";
                    paramValue.Text = ((2500/(dr.GetFloat(0)*0.0048828125)-500)/10)  + " LUX";
                    dr.Close();
                }
                else if (browseSelection == 3)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 7", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();
                    
                    paramTitle.Text = "Irradiation :";
                    paramValue.Text = ((Math.Pow((((dr.GetFloat(0))*1023)/100), 2)/10)/(50)) + " W/m²";
                    dr.Close();
                }
                else if (browseSelection == 4)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `ID`, `VALUE` FROM `SENSORS_STATIC` WHERE ID = 8 OR ID = 5 ORDER BY `ID` DESC", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    double val1 = 161.0 * dr.GetFloat(1) / 5.0 - 25.8;
                    dr.Read();

                    val1  = val1 / (1.0546 - 0.0026 * dr.GetFloat(1));
                    val1 = Math.Round((val1/10.0), 0);
                    if(val1 < 0) val1 = 0;

                    paramTitle.Text = "Humidité :";
                    paramValue.Text = val1 + " %RH";
                    dr.Close();
                }
                else if (browseSelection == 5)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 9", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "V.Vent (Aval) :";
                    paramValue.Text = dr.GetFloat(0) + " KM/H";
                    dr.Close();
                }
                else if (browseSelection == 6)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 10", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "V.Vent (Amon) :";
                    paramValue.Text = dr.GetFloat(0) + " KM/H";
                    dr.Close();
                }
                else if (browseSelection == 7)
                {
                    MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `SENSORS_STATIC` WHERE ID = 11", conn);
                    var dr = cmd.ExecuteReader();
                    dr.Read();

                    paramTitle.Text = "V.Turbine :";
                    paramValue.Text = dr.GetFloat(0) + " TR/MIN";
                    dr.Close();
                }
            }
            conn.Close();
        }
        private void updateGraphSelection()
        {
            return;
        }

        private void updateChargeStatus()
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("SELECT `VALUE` FROM `CHARGES` WHERE 1 ORDER BY `ID` ASC", conn);
            var dr = cmd.ExecuteReader();
            dr.Read();
            if(dr.GetInt32(0) == 1)
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
            browseSelection = 0;
            MaxSelection = 4;
            updateSelection();

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
                    updateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    updateGraphSelection();
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
            updateSelection();

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
                    updateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    updateGraphSelection();
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
            updateSelection();

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
                    updateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    updateGraphSelection();
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
            updateSelection();

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
                    updateSelection();
                }
                else
                {
                    paramTitle.Visible = false;
                    paramValue.Visible = false;
                    updateGraphSelection();
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

        private void updateParams_Tick(object sender, EventArgs e)
        {
            if (isChargePanelEnabled) updateChargeStatus();
            else if (!isGraphEnabled) updateSelection();
            else updateGraphSelection();
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
                updateGraphSelection();
            }
            else
            {
                isGraphEnabled = false;
                Charts.Text = "Passer en mode graphique";
                paramTitle.Visible = true;
                paramValue.Visible = true;
                updateSelection();
            }
        }

        private void Charges_Click(object sender, EventArgs e)
        {
            if (!isChargePanelEnabled)
            {
                Charts.Enabled = false;
                Left_Btn.Enabled = false;
                Right_Btn.Enabled = false;
                paramTitle.Visible = false;
                paramValue.Visible = false;

                Courant_Faible.BackColor = Color.FromArgb(255, 24, 155, 90);
                Courant_Fort.BackColor = Color.FromArgb(255, 24, 155, 90);
                Eolienne.BackColor = Color.FromArgb(255, 24, 155, 90);
                Meteorologie.BackColor = Color.FromArgb(255, 24, 155, 90);
                Charges.BackColor = Color.FromArgb(255, 16, 103, 60);

                label2.Visible = true;
                button1.Visible = true;
                button2.Visible = true;
                button3.Visible = true;
                button4.Visible = true;
                button5.Visible = true;
                button6.Visible = true;
                button7.Visible = true;
                button8.Visible = true;

                updateChargeStatus();
                isChargePanelEnabled = true;
            }
        }

        private void button1_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 1", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button2_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 2", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button3_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 3", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button4_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 4", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button5_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 5", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button6_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 6", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button7_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 7", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }

        private void button8_Click(object sender, EventArgs e)
        {
            conn.Open();
            MySql.Data.MySqlClient.MySqlCommand cmd = new MySql.Data.MySqlClient.MySqlCommand("UPDATE `CHARGES` SET `VALUE` = !`VALUE` WHERE `ID` = 8", conn);
            cmd.ExecuteNonQuery();
            conn.Close();
        }
    }
}