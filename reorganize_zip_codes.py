import re
from collections import defaultdict

def process_file(input_file, output_file):
    # Dictionary to store localities and their zip codes
    locality_data = defaultdict(list)

    with open(input_file, 'r', encoding='utf-8') as file:
        for line in file:
            # Match locality and zip codes
            match = re.match(r"(\d+):([\w\s'-]+)->(.*)", line)
            if match:
                locality_id = match.group(1)
                locality_name = match.group(2).strip()
                zip_codes = match.group(3).replace(" ", "").split(",")
                locality_data[locality_name].extend(zip_codes)
            else:
                # Handle continuation of zip codes
                zip_codes = line.strip().replace(" ", "").split(",")
                if zip_codes and locality_name:
                    locality_data[locality_name].extend(zip_codes)

    # Write the reorganized data to the output file
    with open(output_file, 'w', encoding='utf-8') as file:
        for locality, zip_codes in sorted(locality_data.items()):
            file.write(f"{locality} -> {', '.join(sorted(zip_codes))}\n")

# Input and output file paths
input_file = "C:\wamp64\www\projet-annonce\zipcode.txt"  # Replace with your input file path
output_file = "Reorganized_Zip_Codes.txt"  # Replace with your desired output file path

# Process the file
process_file(input_file, output_file)
print(f"Reorganized file saved to {output_file}")