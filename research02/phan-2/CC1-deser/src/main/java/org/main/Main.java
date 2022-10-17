package org.main;

import java.io.*;

public class Main {
    private static String getResourcePath(String fileName) {
        return "src/main/resources/" + fileName;
    }

    private static void deserializeFromFile(String fileName) {
        try {
            FileInputStream fis = new FileInputStream(getResourcePath(fileName));
            ObjectInputStream ois = new ObjectInputStream(fis);
            ois.readObject();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    public static void main(String[] args) {
        deserializeFromFile("payload_calc.bin");
    }
}

