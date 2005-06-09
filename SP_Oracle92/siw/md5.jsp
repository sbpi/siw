create or replace and compile java source named md5 as
/*  $Header: /cvsroot/plcodebrew/plcodebrew/MD5.java,v 1.2 2003/01/07 21:32:57 cleveridea Exp $

 ============================================================================
        GNU LESSER GENERAL PUBLIC LICENSE Version 2.1, February 1999 
 ============================================================================

 This library is free software; you can redistribute it and/or
 modify it under the terms of the GNU Lesser General Public
 License as published by the Free Software Foundation; either
 version 2.1 of the License, or (at your option) any later version.

 This library is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 Lesser General Public License for more details.

 You should have received a copy of the GNU Lesser General Public
 License along with this library; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

 --
 Copyright (C) 2001 Auriga Logic Pvt. Ltd. <http://www.aurigalogic.com>
 Author: Nikhil Gupte <ngupte@aurigalogic.com>
*/

import java.security.MessageDigest;

/**
 * Provides MD5 Hashing
 *
 * <p>
 * <b>Example:</b> 
 * <br />
 * <code>
 * &nbsp;&nbsp;String hashedString = MD5.hash("hash me");
 * </code>
 * <br /><br />
 * You can run it from the command line as follows:
 * <br />
 * <code>
 * <b>java</b> com.aurigalogic.crypto.MD5 &lt;hash_me&gt;
 * </code>
 * </p>
 * @author <a href="mailto:ngupte@aurigalogic.com">Nikhil Gupte</a>
 * @version $Revision: 1.2 $ $Date: 2003/01/07 21:32:57 $ 
 */
public class MD5 {

	/**
	 * Returns the hashed value of <code>clear</code>.
	 */
	public static String hash(String clear) throws Exception {
		MessageDigest md = MessageDigest.getInstance("MD5");
		byte[] b = md.digest(clear.getBytes());

		int size = b.length;
		StringBuffer h = new StringBuffer(size);
		for (int i = 0; i < size; i++) {
			int u = b[i]&255; // unsigned conversion
			if (u<16) {
				h.append("0"+Integer.toHexString(u));
			} else {
				h.append(Integer.toHexString(u));
			}
		}
		return h.toString();
	}

	/**
	 * Command line tool to get MD5 hash of strings.
	 */
	public static void main(String args[]) {
		try {
			System.out.println("\nMD5: " + hash(args[0]) + "\n");
		} catch (Exception e) {
			usage();	
		}
	}

	/*
	 * Outputs usage info. To be used w/ the main method.
	 */
	private static void usage() {
		System.out.println("Usage: MD5 <string>");	
	}
}
/

