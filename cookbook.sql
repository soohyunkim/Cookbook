DROP TABLE ConsistsOf;
DROP TABLE IncludedStep;
DROP TABLE ManagedCookbook;
DROP TABLE SearchableBy;
DROP TABLE Uses;
DROP TABLE Bookmarks;
DROP TABLE Users;
DROP TABLE Recipe;
DROP TABLE Tag;
DROP TABLE Ingredient;

CREATE TABLE Users (
  email    CHAR(50),
  password CHAR(20),
  type     CHAR(20),
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
  PRIMARY KEY (iName)
);

CREATE TABLE SearchableBy (
  tagName CHAR(50),
  rid     CHAR(20),
  PRIMARY KEY (tagName, rid),
  FOREIGN KEY (tagName) REFERENCES Tag (tagName),
  FOREIGN KEY (rid) REFERENCES Recipe (rid) ON DELETE CASCADE
);

CREATE TABLE Uses (
  rid   CHAR(20),
  iName CHAR(50),
  quantity CHAR(20),
  PRIMARY KEY (rid, iName),
  FOREIGN KEY (rid) REFERENCES Recipe (rid) ON DELETE CASCADE,
  FOREIGN KEY (iName) REFERENCES Ingredient (iName)
);

CREATE TABLE ConsistsOf (
  email CHAR(50),
  cid   CHAR(20),
  rid   CHAR(20),
  PRIMARY KEY (email, cid, rid),
  FOREIGN KEY (email, cid) REFERENCES ManagedCookbook (email, cid) ON DELETE CASCADE ,
  FOREIGN KEY (rid) REFERENCES Recipe (rid) ON DELETE CASCADE
);

CREATE TABLE Bookmarks (
  email CHAR(50),
  rid   CHAR(20),
  PRIMARY KEY (email, rid),
  FOREIGN KEY (email) REFERENCES Users (email) ON DELETE CASCADE ,
  FOREIGN KEY (rid) REFERENCES Recipe (rid) ON DELETE CASCADE
);

INSERT INTO Users (email, password, type) VALUES ('alice123@sample.com', '12345678', 'normal');
INSERT INTO Users (email, password, type) VALUES ('ben010@sample.com', '1231323', 'normal');
INSERT INTO Users (email, password, type) VALUES ('chris.99@sample.com', 'asdfjkl', 'normal');
INSERT INTO Users (email, password, type) VALUES ('david.k@sample.com', '123abc', 'normal');
INSERT INTO Users (email, password, type) VALUES ('emily604@sample.com', '123123123112313', 'normal');
INSERT INTO Users (email, password, type) VALUES ('admin@sample.com', '12345678', 'admin');

INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('1', 'Simple Macaroni and Cheese', 'American', 2, 30);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('2', 'Good Old Fashioned Pancakes', 'American', 1, 20);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('3', 'Pork Dumplings', 'Chinese', 2, 50);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('4', 'Beef Bulgogi', 'Korean', 1, 65);
INSERT INTO Recipe (rid, recipeTitle, cuisine, difficulty, cookingTime) VALUES ('5', 'Guacamole', 'Mexican', 1, 10);

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

INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('elbow macaroni', 'type of pasta noodle', 'good source of carbohydrates');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('butter', 'dairy product containing up to 80% butterfat', 'contains more than 400 different fatty acids');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('flour', 'powder made from grinding raw grains', 'whole wheat is healthier');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('salt', 'salty, used for seasoning', 'source of sodium');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('pepper', 'used alongside salt for seasoning', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('milk', 'white liquid', 'good source of calcium');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('cheddar cheese', 'yellow cheese', 'source of calcium and protein');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('baking powder', 'chemical leavening agent', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('sugar', 'sweet, less powdery than salt or flour; may be white or brown', 'brown sugar is healthier');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('egg', 'comes in a shell, consists of the yolk and the white part', 'high in protein');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('pork', 'pig meat', 'source of protein');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('ginger', 'used as spice', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('green onion', 'long and green, doesn''t look like regular onions', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('soy sauce', 'thin black liquid sauce used for seasoning', 'source of sodium');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('sesame oil', 'oil made from sesame seeds', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('cabbage', 'green vegetable', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('wonton skin', 'used in dumplings', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('beef', 'cow meat, used in steak', 'source of protein');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('garlic', 'beware of bad breath', 'source of vitamin b6');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('sesame seeds', null, null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('black pepper', 'seasoning', null);
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('avocado', 'popular green fruit with a big brown seed in the centre', 'source of potassium');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('lime juice', 'juice made from limes', 'source of vitamin c');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('onion', 'a vegetable that makes you cry', 'source of biotin');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('cilantro', 'a leafy vegetable', 'source of dietary fibre');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('tomato', 'red fruit, often mistaken as a vegetable', 'source of vitamin c');
INSERT INTO Ingredient(iName, description, nutritionalFacts) VALUES ('cayenne pepper', null, null);

INSERT INTO SearchableBy(tagName, rid) VALUES ('pasta', '1');
INSERT INTO SearchableBy(tagName, rid) VALUES ('mac and cheese', '1');
INSERT INTO SearchableBy(tagName, rid) VALUES ('classic', '1');
INSERT INTO SearchableBy(tagName, rid) VALUES ('classic', '2');
INSERT INTO SearchableBy(tagName, rid) VALUES ('pancakes', '2');
INSERT INTO SearchableBy(tagName, rid) VALUES ('dumplings', '3');
INSERT INTO SearchableBy(tagName, rid) VALUES ('bulgogi', '4');
INSERT INTO SearchableBy(tagName, rid) VALUES ('guacamole', '5');
INSERT INTO SearchableBy(tagName, rid) VALUES ('dip', '5');

INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'elbow macaroni','100g');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'butter', '5 tbsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'flour', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'salt', 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'pepper', 'test');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'milk', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('1', 'cheddar cheese', '5g');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'flour', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'baking powder', '5 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'salt', 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'sugar', '50mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'milk', '250mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'egg', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('2', 'butter', '5mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'pork', '1kg');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'ginger', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'garlic', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'green onion', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'soy sauce', '2 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'sesame oil', '1 tbsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'egg', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'cabbage', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'wonton skin', '5');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'beef', '2kg');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'soy sauce', '1 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'sugar', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'green onion', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'garlic', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'sesame seeds', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'sesame oil', '1 tsp');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'black pepper', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'avocado', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'lime juice', '50mL');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'salt', 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'onion', '1/2');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'cilantro', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'tomato',  '2');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'garlic', '1');
INSERT INTO Uses(rid, iName, quantity) VALUES('5', 'cayenne pepper', '10g');
INSERT INTO Uses(rid, iName, quantity) VALUES('3', 'salt', 'pinch of');
INSERT INTO Uses(rid, iName, quantity) VALUES('4', 'salt', 'pinch of');

INSERT INTO ConsistsOf(email, cid, rid) VALUES ('alice123@sample.com', '1', '3');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('alice123@sample.com', '1', '4');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('alice123@sample.com', '1', '2');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('ben010@sample.com', '1', '1');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('ben010@sample.com', '1', '3');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('ben010@sample.com', '1', '4');
INSERT INTO ConsistsOf(email, cid, rid) VALUES ('david.k@sample.com', '1', '5');

INSERT INTO Bookmarks(email, rid) VALUES ('alice123@sample.com', '5');
INSERT INTO Bookmarks(email, rid) VALUES ('alice123@sample.com', '4');
INSERT INTO Bookmarks(email, rid) VALUES ('chris.99@sample.com', '3');
INSERT INTO Bookmarks(email, rid) VALUES ('chris.99@sample.com', '1');
INSERT INTO Bookmarks(email, rid) VALUES ('emily604@sample.com', '1');





