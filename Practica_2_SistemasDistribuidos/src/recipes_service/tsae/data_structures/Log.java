/*
* Copyright (c) Joan-Manuel Marques 2013. All rights reserved.
* DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS FILE HEADER.
*
* This file is part of the practical assignment of Distributed Systems course.
*
* This code is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* This code is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with this code.  If not, see <http://www.gnu.org/licenses/>.
*/

package recipes_service.tsae.data_structures;

import java.io.Serializable;
import java.util.Enumeration;
import java.util.Iterator;
import java.util.List;
import java.util.ListIterator;
import java.util.Vector;
import java.util.concurrent.ConcurrentHashMap;

import edu.uoc.dpcs.lsim.LSimFactory;
import lsim.worker.LSimWorker;
import recipes_service.data.Operation;

/**
 * @author Joan-Manuel Marques, Daniel Lázaro Iglesias
 * December 2012
 *
 */
public class Log implements Serializable{
	// Needed for the logging system sgeag@2017
	private transient LSimWorker lsim = LSimFactory.getWorkerInstance();

	private static final long serialVersionUID = -4864990265268259700L;
	/**
	 * This class implements a log, that stores the operations
	 * received  by a client.
	 * They are stored in a ConcurrentHashMap (a hash table),
	 * that stores a list of operations for each member of 
	 * the group.
	 */
	private ConcurrentHashMap<String, List<Operation>> log= new ConcurrentHashMap<String, List<Operation>>();  

	public Log(List<String> participants){
		// create an empty log
		for (Iterator<String> it = participants.iterator(); it.hasNext(); ){
			log.put(it.next(), new Vector<Operation>());
		}
	}

	/**
	 * inserts an operation into the log. Operations are 
	 * inserted in order. If the last operation for 
	 * the user is not the previous operation than the one 
	 * being inserted, the insertion will fail.
	 * 
	 * @param op
	 * @return true if op is inserted, false otherwise.
	 */
	public boolean add(Operation op){
		if(op == null)
			return false;
		String opHost = op.getTimestamp().getHostid();
		List<Operation> opHostOperation = this.log.get(opHost); //If list is empty add the first operation
		if(opHostOperation == null || opHostOperation.isEmpty()) {
			if(opHostOperation == null) { //If is a new User
				opHostOperation = new Vector<Operation>();
				log.put(opHost, opHostOperation);
			}
			opHostOperation.add(op);
			return true;
		}
		else {
			Timestamp tmHostLastOpe = opHostOperation.get(opHostOperation.size()-1).getTimestamp(); //get the timestamp of last user`s operation
			if(tmHostLastOpe.compare(op.getTimestamp()) < 0) { //If the last operation is previous than the new operation inserted
				opHostOperation.add(op);
				return true;
			}
			else
				return false;
			
		}
	}
	
	/**
	 * Checks the received summary (sum) and determines the operations
	 * contained in the log that have not been seen by
	 * the proprietary of the summary.
	 * Returns them in an ordered list.
	 * @param sum
	 * @return list of operations
	 */
	public List<Operation> listNewer(TimestampVector sum){
		List<Operation> missingOpers = new Vector<Operation>();

        for (String host : this.log.keySet()) {
            List<Operation> opHostOperations = this.log.get(host);
            Timestamp tmHostLastOpe = sum.getLast(host);

            for (Operation op : opHostOperations) {
                if (op.getTimestamp().compare(tmHostLastOpe) > 0) {
                	missingOpers.add(op);
                }
            }
        }
        return missingOpers;
	}
	
	/**
	 * Removes from the log the operations that have
	 * been acknowledged by all the members
	 * of the group, according to the provided
	 * ackSummary. 
	 * @param ack: ackSummary.
	 */
	public void purgeLog(TimestampMatrix ack){
		TimestampVector minTimestampVector = ack.minTimestampVector();
		for (ConcurrentHashMap.Entry<String, List<Operation>> entry : log.entrySet()) {
			Timestamp lastTimestamp = minTimestampVector.getLast(entry.getKey());
			if(lastTimestamp != null){
				List<Operation> hostOpers = entry.getValue();
				List<Integer> idOpersToDelete = new Vector<Integer>(); //List with the IDs of operations to delete.
				for (int i = 0; i < hostOpers.size(); i++) {
	                Operation op = hostOpers.get(i);
	                if (op.getTimestamp().compare(lastTimestamp) <= 0) {
	                	idOpersToDelete.add(i);
	                }
	            }
				for(int i = idOpersToDelete.size()-1; i >= 0 ; i--){
					hostOpers.remove(idOpersToDelete.get(i));
				}
			}
		}
	}

	/**
	 * equals
	 */
	@Override
	public boolean equals(Object obj) {
		if(obj == null)
			return false;
		
		Log objLog = (Log) obj; //cast object to Log
		return objLog.log.equals(this.log);
	}

	/**
	 * toString
	 */
	@Override
	public synchronized String toString() {
		String name="";
		for(Enumeration<List<Operation>> en=log.elements();
		en.hasMoreElements(); ){
		List<Operation> sublog=en.nextElement();
		for(ListIterator<Operation> en2=sublog.listIterator(); en2.hasNext();){
			name+=en2.next().toString()+"\n";
		}
	}
		
		return name;
	}
}