# # TensorFlow model to learn ingredient pairings - based on all ingredients and recipes available to it

# import tensorflow as tf

# - Read in all recipes
#    - For each recipe, make pairings between each ingredient
#    - Output the top 100 pairings (to see it worked)


# Debug - return a dictionary of ingredients and instructions to test the link between the site and this script

import sys, json

# Load the data that the MLContainer provided
try:
    # Get the input data (and remove backslash formatting)
    input_data = sys.argv[1].replace('\\','')
    # Load the data as json
    data = json.loads(input_data)
except Exception as e:
    print(type(e))
    sys.exit(1)

# Output the resulting json
print(json.dumps(data))


# test_recipe = {
#     "title": "\"Blue\" Fettuccine",
#     "author": "'MealMaster' Data",
#     "serves": 4,
#     "ingredients": {
#         "Danish Blue Cheese": {
#             "misc_info": "",
#             "amount": "4",
#             "measure": "ounces",
#             "alternatives": {
#                 "Danish Blue Castello Cheese": {
#                     "misc_info": "chilled",
#                     "amount": "8",
#                     "measure": "ounces"
#                 }
#             }
#         },
#         "Dried Tomatoes": {
#             "misc_info": "marinated",
#             "amount": "1/4",
#             "measure": "cup",
#             "alternatives": {}
#         },
#         "Green Fettuccine": {
#             "misc_info": "",
#             "amount": "8",
#             "measure": "ounces",
#             "alternatives": {
#                 "Spinach Egg Noodles": {
#                     "misc_info": "",
#                     "amount": "1",
#                     "measure": ""
#                 }
#             }
#         },
#         "Shallots": {
#             "misc_info": "minced",
#             "amount": "2",
#             "measure": "tbsp",
#             "alternatives": {}
#         },
#         "Garlic": {
#             "misc_info": "minced",
#             "amount": "1",
#             "measure": "clove",
#             "alternatives": {}
#         },
#         "White Wine": {
#             "misc_info": "dry",
#             "amount": "2",
#             "measure": "tbsp",
#             "alternatives": {}
#         },
#         "Basil": {
#             "misc_info": "finely chopped fresh",
#             "amount": "1 1/2",
#             "measure": "tsp",
#             "alternatives": {
#                 "Dried Basil": {
#                     "misc_info": "",
#                     "amount": "1/2",
#                     "measure": "tsp"
#                 }
#             }
#         },
#         "Parsley": {
#             "misc_info": "chopped fresh",
#             "amount": "1/4",
#             "measure": "cup",
#             "alternatives": {}
#         }
#     },
#     "directions":
#     [
#         "On waxed paper, divide cheese into 10 - 12 pieces; set aside.",
#         "Drain oil from marinated tomatoes, reserving 1 tablespoon.",
#         "Cut tomatoes into thin strips and reserve.",
#         "Prepare fettuccine according to package directions until al dente; drain and return to pot.meanwhile, in small skillet over medium heat, heat reserved oil; add shallots and garlic.saute until shallots are limp but not brown.",
#         "Add wine, basil and reserved tomatoes.heat through and keep hot.add mixture to pasta; toss.add cheese and parsley; toss again to melt cheese.",
#         "Serve immediately on heated plates.typed in mmformat by cindy hartlin.",
#         "Source: woman's day meals in minutes."
#     ]
# }

# # Send it to stdout (to PHP)
# print(json.dumps(test_recipe))
