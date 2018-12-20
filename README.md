## Generate random number file
Run generate-random.php to generate a file of 20GB (more or less).

## Config launcher and read script
Note: the filenames are relative, so cd in the actual working dir.
To run the launcher exec: `/usr/bin/php launcher.php`

The launcher can handle the following params:
	- `-p 40` N part to split in the file
	- `-n 100` N largest numbers to print out at the end

The read python script can be launch by: `/usr/bin/python ./read.py 100 658234236 877645648`
The script can handle the following params:
	- `n_largest` N largest numbers to take
	- `start_byte` number of byte offset from the start of the file
	- `end_byte` number of byte to read from the start byte above


I tested on a machine with 16GB RAM, SSD HD, 4 core 2GHz with 8 simultaneous process with N=1000 and it takes about 5min avg.
I prefer to manage the process in php to split the memory and cpu usage of python process.
I could use thread to share the same memory, but there were not much difference in processing time.

To read a 1GB of this file (buffer mode), without processing, the python script take less than 5sec.
With just the split funciton on the newline the python take more than 8sec, on the same 1GB and so on.. until around 15sec avg to complete the order.

-----

### Consideration

So, this solution it's seems to be linear in time: bigger file or bigger N to consider, a lot of time more to process it. 
In the Big-O notation it could be: O(n)

Another way to accomplish this would be: split the huge file in some little part in tmp file, to recreate 20 GB in a lot of file already sorted (each file can be 100MB or more?), then merge all together to get only the N sorted largest nums. But this will be a lot slower than the actually. In the other hand with this procedure we can have a Big-O like this: O(n log n) ... much better.

I'm gonna bless the unix implementation of `sort`.

Thank you for your time.