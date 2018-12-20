from functools import partial
import mmap
import argparse
import uuid
import json

'''
Read a file by a buffer and iterate over and over until the part is finished
Then save to a file the ordered results (descend order)
'''

def read_file(max_read):
    bufferList = []
    total_read = 0
    # Open the file pointer in read mode then iterate on a small portion (by buffer size)
    with open('data.txt', 'rb') as f:
        for line in f:
            if total_read >= max_read:
                bufferList.sort(reverse=True)
                with open('./tmp/'+str(uuid.uuid4())+'.txt', 'w') as w:
                    w.write(json.dumps(bufferList))
                
                bufferList = []
                total_read = 0

            bufferList.append(line)
            total_read += 1

    # return largest_n[0:N]

#parser = argparse.ArgumentParser("Read huge file")
#parser.add_argument("n_largest", help="N largest numbers in output", type=int)
# parser.add_argument("thread_number", help="N thread to process the file", type=int)
# parser.add_argument("buffer_size", help="Byte of buffer to use", type=int)
# parser.add_argument("start_byte", help="Byte position to start", type=int)
# parser.add_argument("end_byte", help="Byte position when the script end read", type=int)

# args = parser.parse_args()

# Buffer size
BUFFER_SIZE = pow(2, 16) # 14 => 16KB, 16 => 65KB
# N number to be return
#N = args.n_largest

# calc how many byte I need to process
total_read = 0
#max_read = args.end_byte - args.start_byte
# get the largest numbers from this part of file
largest_n = read_file(pow(2,16))

print "end"