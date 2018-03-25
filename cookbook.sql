CREATE TABLE Users (
  email    CHAR(50),
  uName    CHAR(50),
  password CHAR(20),
  PRIMARY KEY (email)
);

CREATE TABLE Recipe (
  rid         CHAR(20),
  recipeTitle CHAR(50),
  cuisine     CHAR(25),
  difficulty  INT,
  cookingTime INT,
  PRIMARY KEY (rid)
);

CREATE TABLE IncludedStep (
  sid         INT,
  rid         CHAR(20),
  instruction CHAR(500),
  PRIMARY KEY (sid, rid),
  FOREIGN KEY (rid) REFERENCES Recipe ON DELETE CASCADE
);

CREATE TABLE Tag (
  tagName CHAR(50),
  PRIMARY KEY (tagName)
);

CREATE TABLE ManagedCookbook (
  cookbookTitle CHAR(50),
  description   CHAR(500),
  cid           CHAR(20),
  email         CHAR(50),
  PRIMARY KEY (email, cid),
  FOREIGN KEY (email) REFERENCES Users (email) ON DELETE CASCADE
);

CREATE TABLE Ingredient (
  iName            CHAR(50),
  description      CHAR(500),
  nutritionalFacts CHAR(500),
  calories INT,
  PRIMARY KEY (iName)
);

CREATE TABLE SearchableBy (
  tagName CHAR(50),
  rid     CHAR(20),
  PRIMARY KEY (tagName, rid),
  FOREIGN KEY (tagName) REFERENCES Tag (tagName),
  FOREIGN KEY (rid) REFERENCES Recipe (rid)
);

CREATE TABLE Uses (
  rid   CHAR(20),
  iName CHAR(50),
  quantity CHAR(20),
  PRIMARY KEY (rid, iName),
  FOREIGN KEY (rid) REFERENCES Recipe (rid),
  FOREIGN KEY (iName) REFERENCES Ingredient (iName)
);

CREATE TABLE ConsistsOf (
  email CHAR(50),
  cid   CHAR(20),
  rid   CHAR(20),
  PRIMARY KEY (email, cid, rid),
  FOREIGN KEY (email, cid) REFERENCES ManagedCookbook (email, cid),
  FOREIGN KEY (rid) REFERENCES Recipe (rid)
);

CREATE TABLE Bookmarks (
  email CHAR(50),
  rid   CHAR(20),
  PRIMARY KEY (email, rid),
  FOREIGN KEY (email) REFERENCES Users (email),
  FOREIGN KEY (rid) REFERENCES Recipe (rid)
);

INSERT INTO Users (email, uName, password) VALUES ('alice123@sample.com', 'Alice', '12345678');
INSERT INTO Users (email, uName, password) VALUES ('ben010@sample.com', 'Ben', '1231323');
INSERT INTO Users (email, uName, password) VALUES ('chris.99@sample.com', 'Chris', 'asdfjkl');
INSERT INTO Users (email, uName, password) VALUES ('david.k@sample.com', 'David', '123abc');
INSERT INTO Users (email, uName, password) VALUES ('emily604@sample.com', 'Emily', '123123123112313');

INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('1', 'Simple Macaroni and Cheese', 'pasta', 2, 30);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('2', 'Good Old Fashioned Pancakes', 'breakfast', 1, 20);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('3', 'Pork Dumplings', 'Asian', 2, 50);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('4', 'Beef Bulgogi', 'Asian', 1, 65);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('5', 'Guacamole', 'Appetizer', 1, 10);

INSERT INTO IncludedStep (sid, rid, instruction) VALUES (1, '1', 'Bring a large pot of lightly salted water to a boil. Cook elbow macaroni in the boiling water, stirring occasionally until cooked through but firm to the bite, 8 minutes. Drain.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (2, '1', 'Melt butter in a saucepan over medium heat; stir in flour, salt, and pepper until smooth, about 5 minutes. Slowly pour milk into butter-flour mixture while continuously stirring until mixture is smooth and bubbling, about 5 minutes. Add Cheddar cheese to milk mixture and stir until cheese is melted, 2 to 4 minutes.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (3, '1', 'Fold macaroni into cheese sauce until coated.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (1, '2', 'In a large bowl, sift together the flour, baking powder, salt and sugar. Make a well in the center and pour in the milk, egg and melted butter; mix until smooth.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (2, '2', 'Heat a lightly oiled griddle or frying pan over medium high heat. Pour or scoop the batter onto the griddle, using approximately 1/4 cup for each pancake. Brown on both sides and serve hot.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (1, '3', 'In a large bowl, combine the pork, ginger, garlic, green onion, soy sauce, sesame oil, egg and cabbage. Stir until well mixed.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (2, '3', 'Place 1 heaping teaspoon of pork filling onto each wonton skin. Moisten edges with water and fold edges over to form a triangle shape. Roll edges slightly to seal in filling. Set dumplings aside on a lightly floured surface until ready to cook.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (3, '3', 'To Cook: Steam dumplings in a covered bamboo or metal steamer for about 15 to 20 minutes. Serve immediately.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (1, '4', 'Place the beef in a shallow dish. Combine soy sauce, sugar, green onion, garlic, sesame seeds, sesame oil, and ground black pepper in a small bowl. Pour over beef. Cover and refrigerate for at least 1 hour or overnight.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (2, '4', 'Quickly grill beef on until slightly charred and cooked through, 1 to 2 minutes per side.');
INSERT INTO IncludedStep (sid, rid, instruction) VALUES (1, '5', 'In a medium bowl, mash together the avocados, lime juice, and salt. Mix in onion, cilantro, tomatoes, and garlic. Stir in cayenne pepper. Refrigerate 1 hour for best flavor, or serve immediately.');

INSERT INTO Tag(tagName) VALUES ('pasta');
INSERT INTO Tag(tagName) VALUES ('mac and cheese');
INSERT INTO Tag(tagName) VALUES ('pancakes');
INSERT INTO Tag(tagName) VALUES ('dumplings');
INSERT INTO Tag(tagName) VALUES ('bulgogi');
INSERT INTO Tag(tagName) VALUES ('guacamole');
INSERT INTO Tag(tagName) VALUES ('dip');
INSERT INTO Tag(tagName) VALUES ('classic');

INSERT INTO ManagedCookbook(cookbookTitle, description, cid, email) VALUES ('Good Asian Recipes', 'Favourite Asian recipes', '1', 'alice123@sample.com');
INSERT INTO ManagedCookbook(cookbookTitle, description, cid, email) VALUES ('Breakfast', 'Delicious breakfast recipes', '2', 'alice123@sample.com');
INSERT INTO ManagedCookbook(cookbookTitle, description, cid, email) VALUES ('Dinner', 'Recipes for Dinner', '1', 'ben010@sample.com');
INSERT INTO ManagedCookbook(cookbookTitle, description, cid, email) VALUES ('Pasta Ideas', 'New pasta recipes to try', '1', 'chris.99@sample.com');
INSERT INTO ManagedCookbook(cookbookTitle, description, cid, email) VALUES ('My Favourite Appies', 'Favourite Appetizers', '1', 'david.k@sample.com');

INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('elbow macaroni', 'type of pasta noodle', 'good source of carbohydrates', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('butter', 'dairy product containing up to 80% butterfat', 'contains more than 400 different fatty acids', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('flour', 'powder made from grinding raw grains', 'whole wheat is healthier', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('salt', 'salty, used for seasoning', 'source of sodium', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('pepper', 'used alongside salt for seasoning', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('milk', 'white liquid', 'good source of calcium', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('cheddar cheese', 'yellow cheese', 'source of calcium and protein', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('baking powder', 'chemical leavening agent', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('sugar', 'sweet, less powdery than salt or flour; may be white or brown', 'brown sugar is healthier', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('egg', 'comes in a shell, consists of the yolk and the white part', 'high in protein', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('pork', 'pig meat', 'source of protein', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('ginger', 'used as spice', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('green onion', 'long and green, doesn''t look like regular onions', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('soy sauce', 'thin black liquid sauce used for seasoning', 'source of sodium', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('sesame oil', 'oil made from sesame seeds', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('cabbage', 'green vegetable', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('wonton skin', 'used in dumplings', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('beef', 'cow meat, used in steak', 'source of protein', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('garlic', 'beware of bad breath', 'source of vitamin b6', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('sesame seeds', null, null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('black pepper', 'seasoning', null, 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('avocado', 'popular green fruit with a big brown seed in the centre', 'source of potassium', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('lime juice', 'juice made from limes', 'source of vitamin c', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('onion', 'a vegetable that makes you cry', 'source of biotin', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('cilantro', 'a leafy vegetable', 'source of dietary fibre', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('tomato', 'red fruit, often mistaken as a vegetable', 'source of vitamin c', 100);
INSERT INTO Ingredient(iName, description, nutritionalFacts, calories) VALUES ('cayenne pepper', null, null, 100);

INSERT INTO SearchableBy(tagName, rid) VALUES ('pasta', '1');
INSERT INTO SearchableBy(tagName, rid) VALUES ('mac and cheese', '1');
INSERT INTO SearchableBy(tagName, rid) VALUES ('classic', '1');
INSERT INTO SearchableBy(tagName, rid) VALUES ('classic', '2');
INSERT INTO SearchableBy(tagName, rid) VALUES ('pancakes', '2');
INSERT INTO SearchableBy(tagName, rid) VALUES ('dumplings', '3');
INSERT INTO SearchableBy(tagName, rid) VALUES ('bulgogi', '4');
INSERT INTO SearchableBy(tagName, rid) VALUES ('guacamole', '5');
INSERT INTO SearchableBy(tagName, rid) VALUES ('dip', '5');

INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'elbow macaroni', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'butter', '5 tbsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'flour', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'salt', 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'pepper', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'milk', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'cheddar cheese', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'flour', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'baking powder', '5 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'salt' 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'sugar', '50mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'milk', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'egg', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'butter', '5mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'pork', '1kg');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'ginger', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'garlic', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'green onion', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'soy sauce', '2 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'sesame oil', '1 tbsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'egg', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'cabbage', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'wonton skin', '5');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'beef', '2kg');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'soy sauce', '1 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'sugar', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'green onion', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'garlic', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'sesame seeds', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'sesame oil', '1 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'black pepper', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'avocado', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'lime juice', '50mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'salt', 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'onion', '1/2');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'cilantro', null);
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'tomato',  '2');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'garlic', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'cayenne pepper', null);

INSERT INTO ConsistsOf(email, cid, rid) VALUES ('alice123@sample.com', '1', '3');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('alice123@sample.com', '1', '4');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('alice123@sample.com', '2', '2');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('ben010@sample.com', '1', '1');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('ben010@sample.com', '1', '3');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('ben010@sample.com', '1', '4');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('david.k@sample.com', '1', '5');

INSERT INTO Bookmarks(email, rid) VALUES ('alice123@sample.com', '5');
INSERT INTO Bookmarks(email, rid) VALUES ('alice123@sample.com', '4');
INSERT INTO Bookmarks(email, rid) VALUES ('chris.99@sample.com', '3');
INSERT INTO Bookmarks(email, rid) VALUES ('chris.99@sample.com', '1');
INSERT INTO Bookmarks(email, rid) VALUES ('emily604@sample.com', '1');





