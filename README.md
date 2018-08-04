# punchmark-devtest
Developer test for Punchmark

 + My task was to create a command line PHP script that reads two CSV files, and outputs one final CSV file with the correct columns taken from both CSV files. Each line of the first CSV file needed to be matched with the correct line of the second CSV file using the Style # field, so that all the columns in each row represent the same item.

 + The output file needed to contain all the products from the updated CSV: 'Style #', 'Item #', 'Item Title', 'Unit Price', 'MSRP', 'On-Hand', 'WebDescription', 'Collection', 'Product Type', and 'Image' in that order.

 + The columns: 'Style #', 'Item #', 'Unit Price', 'MSRP', 'On-Hand', and 'WebDescription' needed to come from the updated inventory CSV. The columns: 'Item Title', 'Collection', 'Product Type', and 'Image' come from the original inventory CSV.

+ The information in the two CSV files must be matched by 'Style #' for the data to be correct. The order of the products in the updated CSV do not match the order of the products in the orignal CSV and some products may be missing from each one.

+ Baseline task is to build a script that will output the number of duplicate Style #s, if any, in the update CSV file, as well as any products not found in the original CSV, like this: 
Duplicate Style #s: 99
Items missing from Original CSV: 99

+ Ideally, the solution will only read through each file once.
