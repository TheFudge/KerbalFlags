/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
package kerbanflagsupdater;

/**
 *
 * @author Kasimir
 */
public class Flaggen {
    private int id;
    private String pid;
    private String name;
    private String lines;
    public Flaggen(int id, String p, String n)
    {
        this.id = id;
        pid = p;
        name = n;
    }
    
    public void AddLine(String line)
    {
        lines = lines + "\r\n" + line;
    }
}
