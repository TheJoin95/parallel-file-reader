from functools import partial
import mmap
import argparse
import uuid
import json

'''
Read a file by a buffer and iterate over and over until the part is finished
Then save to a file the ordered results (descend order)
'''

def read_file(N, max_read, buffer_size):
    largest_n = []
    total_read = 0
    # Open the file pointer in read mode then iterate on a small portion (by buffer size)
    with open('data.txt', 'rb') as f:
        for chunk in iter(partial(f.read, buffer_size), b''):
            if total_read >= max_read:
                return largest_n

            # I can write here a file of 100MB with the sorted unique list
            # these things right here take 40sec to be done for 1GB

            # increment the counter to stop when I read the whole part
            total_read += buffer_size
            # Get the lines
            chunk = chunk.split("\n")
            # Sort the lines in descent ord
            chunk.sort(reverse=True)
            # take only N number
            largest_n = largest_n + chunk[0:N]
            # unique these N number
            largest_n = list(set(largest_n[0:N])) # unique
            # Order the whole list f N
            largest_n.sort(reverse=True)

    # return largest_n[0:N]

parser = argparse.ArgumentParser("Read huge file")
parser.add_argument("n_largest", help="N largest numbers in output", type=int)
# parser.add_argument("thread_number", help="N thread to process the file", type=int)
# parser.add_argument("buffer_size", help="Byte of buffer to use", type=int)
parser.add_argument("start_byte", help="Byte position to start", type=int)
parser.add_argument("end_byte", help="Byte position when the script end read", type=int)

args = parser.parse_args()

# Buffer size
BUFFER_SIZE = pow(2, 14) # 14 => 16KB, 16 => 65KB
# N number to be return
N = args.n_largest

# calc how many byte I need to process
total_read = 0
max_read = args.end_byte - args.start_byte
# get the largest numbers from this part of file
largest_n = read_file(N, max_read, BUFFER_SIZE)

# I write the list sorted in a file
with open('./results/result.'+str(uuid.uuid4())+'.txt', 'w') as f:
    f.write(json.dumps(largest_n))

# debug
print largest_n