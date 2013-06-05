/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package kerbanflagsupdater;

/**
 *
 * @author Kasimir
 */
public class Counter {
    
        private int vessels;
        private int flags;
        public Flaggen flag[] = new Flaggen[1024];
        
    public Counter()
    {
        vessels = 0;
        flags = 0;
    }
    public int Vessel(boolean flag)
    {
        vessels++;
        if(flag == true)
        {
            flags++;
        }
        return flags;
    }
    public int AddFlag(String a, String b)
    {
        int count = Vessel(true);
        flag[count] = new Flaggen(count, a, b);
        return count;
    }
    public void ModifyFlag(int flagid, String zeile)
    {
        flag[flagid].AddLine(zeile);
    }
    public String getInfo()
    {
        return "Found " + vessels + " Vessel Informations. "+flags+" of them were flags.";
    }
}

