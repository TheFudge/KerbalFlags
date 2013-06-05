/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package kerbanflagsupdater;

import java.io.BufferedReader;
import java.io.File;
import java.io.FileReader;

/**
 *
 * @author Kasimir
 */
public class KerbanFlagsUpdater {

    /**
     * @param args the command line arguments
     */
    String[] data = null;
    Counter c = new Counter();
    
    public KerbanFlagsUpdater()
    {
        System.out.println("STARTING!");
        System.out.println(checkFile("E:\\Programme\\Games\\Steam\\SteamApps\\common\\Kerbal Space Program\\saves\\Fudge Space Travels\\persistent.sfs"));
    }
    
    public static void main(String[] args) {
        KerbanFlagsUpdater k = new KerbanFlagsUpdater();
        
    }
    public int checkFile(String filename)
    {
        File file = new File(filename);
        String[] zeile = new String[786432];
        System.out.println("FILE EXISTENCE: "+file.exists() + " IS FILE: " + file.isFile());
        if(file.exists()){
            try{
                    // Open the file that is the first 
                    // command line parameter
                    //FileInputStream fstream = new FileInputStream(filename);
                    // Get the object of DataInputStream
                    //DataInputStream in = new DataInputStream(fstream);
                    BufferedReader br = new BufferedReader(new FileReader(filename));
                    String strLine;
                    //Read File Line By Line
                    
                    int i = 1;
                    while ((strLine = br.readLine()) != null)
                    {
                       //System.out.println("LINE OUTPUT: "+strLine);
                       zeile[i] = strLine.trim();
                       i++;
                    }
                    zeile[0] = String.valueOf(i);
                    //Close the input stream
                    br.close();
                    
                      }catch (Exception e){//Catch exception if any
                          e.printStackTrace();
                        System.err.println("Error: " + e.getMessage());
                        return 0; // Error
                    }
  
            return checkGame(zeile);
        }
        else{
            return -1; // File doesn't exist
        }
        
    }
    public String strReplace(String[] from, String[] to, String s){
        for(int i=0; i<from.length; i++){
          s = s.replaceAll(from[i], to[i]);
        }
        return s;
      }
    private int checkGame(String[] zeile)
    {
        if(zeile[1].equals("GAME"))
        {
            // IN GAME REIHUNG
            int count = Integer.parseInt(zeile[0]);
            String version = zeile[3].replace("version = ", "");
            int start = -1;
            int check = 0;
            for(int b = 4; b < count; b++)
            {
                if(zeile[b].equals("FLIGHTSTATE"))
                {
                    // FETCHING ALL VESSEL FLIGHTS AND CHECKING FOR TYPE
                    start = b;
                    
                }
                check = b;
            }
            if(start == -1)
            {
                System.out.println("LINE: " + check);
                return -3;
            }
            else
            {
                return checkVessel(zeile, start, count);
            }
        }
        else
        {
            System.out.println("->"+zeile[1]+"<-");
            return -2;
        }
    }
    private int checkVessel(String[] zeile, int start, int count)
    {
        for(int b = start; b < count; b++)
        {
            if(zeile[b].equals("VESSEL"))
            {
                // b is start of a vessel_information! 
                System.out.println("Found a Vessel! Vessel type: "+zeile[b+4] + " Name: "+zeile[b+3]);
                if(zeile[b+4].equals("type = Flag"))
                {
                    // VESSEL INFO IS ABOUT THE FLAG
                    System.out.println("That's a FLAG!");
                    int flagid = c.AddFlag(zeile[b], zeile[b]);
                    saveFlag(flagid, b, count, zeile);
                }
                else
                {
                    c.Vessel(false);
                    // no Flag
                }
            }
        }
        System.out.println(c.getInfo());
        
        return 0;
    }
    public void saveFlag(int flagid, int start, int count, String[] zeile)
    {
        for(int d = start; d < count; d++)
        {
            c.ModifyFlag(flagid, zeile[d]);
        }
    }
}
