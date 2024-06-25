DROP TABLE IF EXISTS BuyerProductsOffered;
CREATE TABLE BuyerProductsOffered
(
    ChatId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL CHECK(Quantity >= 0),
    PRIMARY KEY (ChatId, ProductId),
    FOREIGN KEY (ChatId) REFERENCES Chat (ChatId),
    FOREIGN KEY (ProductId) REFERENCES Item (ItemId)
  --to add triggers
);


DROP TABLE IF EXISTS Brand;
CREATE TABLE Brand 
(
    BrandId INTEGER PRIMARY KEY NOT NULL,
    CategoryId INTEGER,
    Name TEXT,
    FOREIGN KEY (CategoryId) REFERENCES Category (CategoryId)
);


DROP TABLE IF EXISTS Model;
CREATE TABLE Model 
(
    ModelId INTEGER PRIMARY KEY NOT NULL,
    BrandId INTEGER NOT NULL,
    Name TEXT,
    FOREIGN KEY (BrandId) REFERENCES Brand (BrandId)
);


DROP TABLE IF EXISTS Category;
CREATE TABLE Category
(
  CategoryId INTEGER PRIMARY KEY NOT NULL,
  Name TEXT,
  ParentId INTEGER,
  Description TEXT,
  FOREIGN KEY (ParentId) REFERENCES Category (CategoryId)
  --to add triggers
);


DROP TABLE IF EXISTS Chat ;
CREATE TABLE Chat 
(
    ChatId INTEGER PRIMARY KEY NOT NULL,
    SellerId INTEGER NOT NULL,
    BuyerId INTEGER NOT NULL, 
    BuyerMoney DOUBLE PRECISION NOT NULL CHECK(BuyerMoney >= 0),
    SellerMoney DOUBLE PRECISION NOT NULL CHECK(SellerMoney >= 0),
    FOREIGN KEY (SellerId) REFERENCES User (UserId),
    FOREIGN KEY (BuyerId) REFERENCES User (UserId)
  --to add triggers
);


DROP TABLE IF EXISTS Condition;
CREATE TABLE  Condition
(
  ConditionId INTEGER PRIMARY KEY NOT NULL,
  Name TEXT
  --to add triggers
);


DROP TABLE IF EXISTS Image;
CREATE TABLE Image
(
    ImageId INTEGER PRIMARY KEY NOT NULL, 
    ImageName TEXT,
    ImageData BLOB
  -- to add triggers
);


DROP TABLE IF EXISTS ImageItem;
CREATE TABLE ImageItem
(
  ItemId INTEGER NOT NULL,
  ImageId INTEGER NOT NULL, 
  PRIMARY KEY (ItemId, ImageId),
  FOREIGN KEY (ItemId) REFERENCES Item (ItemId),
  FOREIGN KEY (ImageId) REFERENCES Image (ImageId)
  -- to add triggers
);

DROP TABLE IF EXISTS Coupon;
CREATE TABLE Coupon
(
    CouponId INTEGER PRIMARY KEY NOT NULL,
    Code TEXT NOT NULL UNIQUE,
    Discount DOUBLE PRECISION NOT NULL CHECK(Discount >= 0 AND Discount <= 100),
    ExpiryDate TEXT NOT NULL,
    Items JSON NOT NULL DEFAULT '{}' CHECK(JSON_VALID(Items)),
    SellerId INTEGER NOT NULL,
    BuyerId INTEGER NOT NULL,
    FOREIGN KEY (SellerId) REFERENCES User (UserId),
    FOREIGN KEY (BuyerId) REFERENCES User (UserId)
);


DROP TABLE IF EXISTS Item;
CREATE TABLE Item
(
    ItemId INTEGER PRIMARY KEY NOT NULL,
    Name TEXT NOT NULL,
    Description TEXT,
    Stock INTEGER NOT NULL CHECK(Stock >= 0),
    BrandId INTEGER,
    ModelId INTEGER,
    Price DOUBLE PRECISION NOT NULL,
    UNAVAILABLE BOOLEAN NOT NULL,
    OwnerId INTEGER NOT NULL,
    CategoryId INTEGER,
    SizeId INTEGER,
    ConditionId INTEGER,
    ImageCount INTEGER NOT NULL CHECK(ImageCount >= 0),
    FOREIGN KEY (OwnerId) REFERENCES User (UserId),
    FOREIGN KEY (CategoryId) REFERENCES Category (CategoryId),
    FOREIGN KEY (SizeId) REFERENCES Size (SizeId),
    FOREIGN KEY (ModelId) REFERENCES Model (ModelId),
    FOREIGN KEY (BrandId) REFERENCES Brand (BrandId)
    -- to add triggers
);


DROP TABLE IF EXISTS ItemReview;
CREATE TABLE ItemReview
(
    ReviewerId INTEGER NOT NULL,
    ReviewedId INTEGER NOT NULL,
    StarsNumber INTEGER NOT NULL CHECK(StarsNumber >= 0 AND StarsNumber <= 5),
    Description TEXT,
    PRIMARY KEY (ReviewerId, ReviewedId),
    FOREIGN KEY (ReviewerId) REFERENCES User (UserId),
    FOREIGN KEY (ReviewedId) REFERENCES Item (ItemId)
  -- to add triggers
);


DROP TABLE IF EXISTS Message;
CREATE TABLE Message
(
  MessageId INTEGER PRIMARY KEY NOT NULL,
  MessageTime TEXT NOT NULL,
  MessageText TEXT NOT NULL, 
  MessengerId INTEGER NOT NULL,
  ChatId INTEGER NOT NULL,
  FOREIGN KEY (ChatId) REFERENCES Chat (ChatId)
  --to add triggers
);


DROP TABLE IF EXISTS SellerProductsOffered;
CREATE TABLE SellerProductsOffered
(
    ChatId INTEGER NOT NULL,
    ProductId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL CHECK(Quantity >= 0),
    PRIMARY KEY (ChatId, ProductId),
    FOREIGN KEY (ChatId) REFERENCES Chat (ChatId),
    FOREIGN KEY (ProductId) REFERENCES Item (ItemId)
    --to add triggers
);


DROP TABLE IF EXISTS ShoppingCart;
CREATE TABLE ShoppingCart 
(
  UserId INTEGER NOT NULL, 
    ItemId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL CHECK(Quantity >= 0),
    PRIMARY KEY (UserId, ItemId),
    FOREIGN KEY (UserId) REFERENCES User (UserId),
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId)
  --to add triggers
);


DROP TABLE IF EXISTS Size;
CREATE TABLE Size
(
    SizeId INTEGER PRIMARY KEY NOT NULL,
    Name TEXT
  --to add triggers
);


DROP TABLE IF EXISTS User;
CREATE TABLE User
(
  UserId  INTEGER NOT NULL, 
  Username TEXT NOT NULL UNIQUE,
  Password TEXT NOT NULL,
  Name TEXT NOT NULL,
  Email TEXT NOT NULL UNIQUE,
  PhoneNumber VARCHAR(9), /*Can be changed*/
  Address VARCHAR(255), /*Can be changed*/
  Description VARCHAR(255), /*Can be changed*/
  AdminStatus BOOLEAN NOT NULL DEFAULT 0,
  PRIMARY KEY (UserId)
  -- to add triggers
);


DROP TABLE IF EXISTS UserReview;
CREATE TABLE UserReview 
(
    ReviewerId INTEGER NOT NULL,
    ReviewedId INTEGER NOT NULL,
    StarsNumber INTEGER NOT NULL CHECK(StarsNumber >= 0 AND StarsNumber <= 5),
    Description TEXT,
    PRIMARY KEY (ReviewerId, ReviewedId),
    FOREIGN KEY (ReviewerId) REFERENCES User (UserId),
    FOREIGN KEY (ReviewedId) REFERENCES User (UserId)
  -- to add  contraints,triggers
);


DROP TABLE IF EXISTS Wishlist;
CREATE TABLE Wishlist
(
    UserId INTEGER NOT NULL, 
    ItemId INTEGER NOT NULL,   
    PRIMARY KEY (UserId, ItemId),
    FOREIGN KEY (UserId) REFERENCES User (UserId),
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId)
  --to add triggers
);


DROP TABLE IF EXISTS ShippingOrder;
CREATE TABLE ShippingOrder
(
    OrderId INTEGER PRIMARY KEY NOT NULL,
    OwnerId INTEGER NOT NULL, 
    BuyerId INTEGER NOT NULL,
    BuyerAddress INTEGER NOT NULL,
    BuyerCity INTEGER NOT NULL,
    BuyerPostalCode INTEGER NOT NULL,
    BuyerCountry INTEGER NOT NULL,
    FOREIGN KEY (OwnerId) REFERENCES User (UserId),
    FOREIGN KEY (BuyerId) REFERENCES User (UserId)
  --to add triggers
);


DROP TABLE IF EXISTS ItemsToSend;
CREATE TABLE ItemsToSend
(
    OrderId INTEGER NOT NULL,
    ItemId INTEGER NOT NULL,
    Quantity INTEGER NOT NULL CHECK(Quantity >= 0),
    PRIMARY KEY (OrderId, ItemId),
    FOREIGN KEY (OrderId) REFERENCES ShippingOrder (OrderId),
    FOREIGN KEY (ItemId) REFERENCES Item (ItemId)
  --to add triggers
);


INSERT INTO "Size" (SizeId, Name)
VALUES
    (1, 'XS'),
    (2, 'S'),
    (3, 'M'),
    (4, 'L'),
    (5, 'XL'),
    (6, 'XXL'),
    (7, 'EU 36'),
    (8, 'EU 37'),
    (9, 'EU 38'),
    (10, 'EU 39'),
    (11, 'EU 40'),
    (12, 'EU 41'),
    (13, 'EU 42'),
    (14, 'EU 43'),
    (15, 'EU 44'),
    (16, 'EU 45');

-- Insert data into Condtion table
INSERT INTO "Condition" (ConditionId, Name)
VALUES
    (1, 'New'),
    (2, 'Used'),
    (3, 'Refurbished'),
    (4, 'Like New'),
    (5, 'Fair'),
    (6, 'Poor');

-- Insert data into Category table
INSERT INTO "Category" ("CategoryId", "Name", "ParentId", "Description") VALUES
	(1, 'Footwear', NULL, 'Shoes and sneakers'),
	(2, 'Accessories', NULL, ''),
	(3, 'Woman''s Clothes', 1, ''),
	(4, 'Man''s Clothes', 1, ''),
	(5, 'Children''s Clothes', NULL, NULL);

-- Insert data into Brand table
INSERT INTO Brand (BrandId, CategoryId, Name)
VALUES
    (1, 1, 'Nike'),
    (2, 1, 'Adidas'),
    (3, 1, 'Puma'),
    (4, 1, 'Converse'),
    (5, 1, 'Vans'),
    (6, 1, 'New Balance'),
    (7, 2, 'Ray-Ban'),
    (8, 2, 'Gucci'),
    (9, 2, 'Louis Vuitton'),
    (10, 2, 'Prada'),
    (11, 3, 'Zara'),
    (12, 3, 'H&M'),
    (13, 3, 'Forever 21'),
    (14, 3, 'Mango'),
    (15, 4, 'Levi''s'),
    (16, 4, 'Tommy Hilfiger'),
    (17, 4, 'Calvin Klein'),
    (18, 4, 'Ralph Lauren'),
    (19, 5, 'Carter''s'),
    (20, 5, 'Gap'),
    (21, 5, 'Old Navy'),
    (22, 5, 'H&M');

-- Insert data into Model table
INSERT INTO Model (ModelId, BrandId, Name)
VALUES
    (1, 1, 'Air Max 90'),
    (2, 1, 'Air Force 1'),
    (3, 2, 'Superstar'),
    (4, 2, 'Ultraboost'),
    (5, 3, 'Cali'),
    (6, 3, 'Roma'),
    (7, 4, 'Chuck Taylor All Star'),
    (8, 4, 'Chuck 70'),
    (9, 5, 'Old Skool'),
    (10, 5, 'Sk8-Hi'),
    (11, 6, '574'),
    (12, 6, '990'),
    (13, 7, 'Wayfarer'),
    (14, 7, 'Aviator'),
    (15, 8, 'GG Marmont'),
    (16, 8, 'Soho'),
    (17, 9, 'Neverfull'),
    (18, 9, 'Speedy'),
    (19, 10, 'Galleria'),
    (20, 10, 'Cahier'),
    (21, 11, 'Basic T-Shirt'),
    (22, 11, 'Skinny Jeans'),
    (23, 12, 'Blouse'),
    (24, 12, 'Sweater'),
    (25, 13, 'Crop Top'),
    (26, 13, 'Shorts'),
    (27, 14, 'Dress'),
    (28, 14, 'Jumpsuit'),
    (29, 15, '501'),
    (30, 15, 'Trucker Jacket'),
    (31, 16, 'Tommy Jeans'),
    (32, 16, 'Polo Shirt'),
    (33, 17, 'Boxer Briefs'),
    (34, 17, 'T-Shirt'),
    (35, 18, 'Polo Shirt'),
    (36, 18, 'Chino Pants'),
    (37, 19, 'Bodysuit'),
    (38, 19, 'Footed Pajamas'),
    (39, 20, 'Zip Hoodie'),
    (40, 20, 'Cargo Pants'),
    (41, 21, 'Bodysuit'),
    (42, 21, 'Tutu Dress'),
    (43, 22, 'Onesie'),
    (44, 22, 'Leggings');

-- Insert data into Image table
INSERT INTO Image (ImageId, ImageName, ImageData)
VALUES
    (1, 'shoe1.jpg', 'base64encodeddata'),
    (2, 'phone1.jpg', 'base64encodeddata');

-- Insert data into User table
INSERT INTO User (UserId, Username, Password, Name, Email, AdminStatus)
VALUES
    (1, 'john_doe', '$2y$10$hFV/UyBmuNRk.MpchNLIq.uHrkY5PK4eBq0whdwecvbnW1HLMSZSC', 'John Doe', 'john@example.com', 0),
    (2, 'jane_smith', '$2y$10$xv/iV304F0pYIvfGzAEoxebeF8tp.AOfjoLQLspn7Q6kiNIPAvCRC', 'Jane Smith', 'jane@example.com', 1);

-- Insert data into Item table
INSERT INTO Item (ItemId,Name,Description,Stock, BrandId, ModelId, Price, UNAVAILABLE, OwnerId, CategoryId, SizeId, ConditionId, ImageCount)
VALUES
    (1,"Nike Air Max 90 Essential Men's Shoes - Black/White","Step into iconic style with the Nike Air Max 90 Essential, a timeless sneaker that blends comfort and heritage design. These men's shoes feature a sleek black and white colorway, offering a versatile look for everyday wear. The upper is crafted from durable leather and synthetic materials, providing support and a premium feel. Visible Max Air cushioning in the heel ensures responsive comfort with every step. A padded collar and foam midsole offer additional cushioning and support. Finished with the classic waffle-pattern outsole for traction and durability, these Nike Air Max 90 Essential shoes are a must-have for sneaker enthusiasts and casual wear alike.",3, 1, 1, 100, 0, 1, 1, 1, 1,1),
    (2,"Adidas Originals Superstar Men's Sneakers - White/Black","Elevate your street style with the Adidas Originals Superstar sneakers, a true icon of urban fashion. These men's shoes feature a classic white leather upper with signature black stripes and heel tab, embodying the timeless Superstar look. The shell toe design pays homage to its basketball roots, while the rubber cupsole ensures durability and traction. A comfortable textile lining and padded insole offer all-day comfort. Whether you're pairing them with jeans for a casual look or dressing them up with athleisure wear, the Adidas Superstar sneakers deliver versatile style with a heritage feel. Add a touch of retro flair to your wardrobe with these legendary kicks.",7 ,2, 3, 150, 0, 1, 1, 2, 1,1),
    (3, "Puma Cali Women's Sneakers - White/Black", "Make a statement with the Puma Cali women's sneakers, a modern take on classic California style. These sneakers feature a sleek white leather upper with contrasting black accents for a bold look. The perforated details and Puma branding add to the sporty vibe, while the padded collar and cushioned insole provide all-day comfort. With a chunky rubber sole for traction and durability, these Puma Cali sneakers are perfect for completing your casual-chic ensemble.", 5, 3, 5, 120, 0, 1, 1, 2, 1, 1),
    (4, "Converse Chuck Taylor All Star High Top Sneakers - Black", "Step up your sneaker game with the Converse Chuck Taylor All Star high top sneakers, an iconic silhouette that never goes out of style. These classic black high tops feature a durable canvas upper and rubber toe cap for a timeless look and lasting wear. The lace-up closure ensures a secure fit, while the cushioned footbed provides comfort for all-day wear. Finished with the signature Chuck Taylor All Star logo patch on the ankle, these Converse high tops are a must-have for any sneaker collection.", 8, 4, 7, 70, 0, 1, 1, 3, 1, 1),
    (5, "Vans Old Skool Unisex Skate Shoes - Black/White", "Rock a classic skate style with the Vans Old Skool unisex skate shoes, a timeless favorite for streetwear enthusiasts. These low-top sneakers feature a durable canvas and suede upper with the iconic Vans side stripe for a signature look. The padded collar and footbed provide cushioning and support, while the vulcanized rubber sole offers superior grip and boardfeel. Whether you're hitting the skate park or strolling the city streets, the Vans Old Skool shoes deliver versatile style and unbeatable comfort.", 10, 5, 9, 65, 0, 1, 1, 4, 1, 1),
    (6, "New Balance 574 Men's Lifestyle Shoes - Grey/Blue", "Stride in style with the New Balance 574 men's lifestyle shoes, a classic sneaker that blends retro charm with modern comfort. These shoes feature a stylish grey suede and mesh upper with blue accents for a pop of color. The ENCAP midsole cushioning provides support and stability, while the durable rubber outsole offers traction and durability. Whether you're running errands or exploring the city, the New Balance 574 sneakers offer timeless appeal and all-day comfort.", 6, 6, 11, 90, 0, 1, 1, 5, 1, 1),
    (7, "Ray-Ban Wayfarer Classic Sunglasses - Black/Green", "Stay cool and stylish with the Ray-Ban Wayfarer Classic sunglasses, an iconic accessory that has stood the test of time. These sunglasses feature a sleek black acetate frame and green G-15 lenses for a classic look with excellent clarity and protection. The square shape and wide temples offer a bold silhouette, while the adjustable nose pads ensure a comfortable fit. Whether you're lounging poolside or cruising in the city, the Ray-Ban Wayfarer sunglasses add a touch of retro-cool to any outfit.", 12, 7, 13, 150, 0, 1, 2, 1, 1, 1),
    (8, "Gucci GG Marmont Matelassé Leather Wallet - Black", "Elevate your accessories collection with the Gucci GG Marmont Matelassé leather wallet, a luxurious essential for the modern fashionista. Crafted in Italy from supple black leather, this wallet features the iconic GG logo and chevron quilting for a sophisticated look. The snap closure opens to reveal multiple card slots, a zip pocket, and space for bills, ensuring you stay organized in style. Whether tucked into your favorite handbag or carried solo, the Gucci GG Marmont wallet exudes timeless elegance and impeccable craftsmanship.", 9, 8, 15, 550, 0, 1, 2, 2, 1, 1),
    (9, "Louis Vuitton Neverfull MM Tote Bag - Monogram Canvas", "Make a statement with the Louis Vuitton Neverfull MM tote bag, a classic piece that combines fashion and function in one iconic design. Crafted from durable Monogram canvas with natural cowhide leather trim, this tote features slim handles for comfortable carrying, a spacious interior with a removable zip pouch, and side laces for adjustable capacity. Whether you're running errands or jet-setting across the globe, the Louis Vuitton Neverfull tote is the perfect companion for any occasion.", 4, 9, 17, 1500, 0, 1, 2, 3, 1, 1),
    (10, "Prada Galleria Saffiano Leather Handbag - Black", "Indulge in luxury with the Prada Galleria Saffiano leather handbag, a timeless accessory that exudes sophistication and style. Crafted in Italy from premium Saffiano leather, this structured tote features the iconic Prada triangle logo and signature gold-tone hardware. The spacious interior is lined in luxurious leather and includes multiple pockets for organizing your essentials. With its timeless silhouette and versatile design, the Prada Galleria handbag is the epitome of elegance for the modern woman.", 3, 10, 19, 2200, 0, 1, 2, 4, 1, 1),
    (11, "Zara Basic T-Shirt - White", "Upgrade your basics with the Zara Basic T-shirt, a wardrobe essential that offers comfort and style in equal measure. This classic white tee is crafted from soft cotton jersey for a breathable feel and features a crew neckline and short sleeves for a timeless look. Whether worn on its own or layered under a jacket or sweater, the Zara Basic T-shirt is a versatile staple for any casual ensemble.", 15, 11, 21, 20, 0, 1, 3, 1, 1, 1),
    (12, "H&M Blouse - Floral Print", "Add a touch of femininity to your wardrobe with the H&M blouse, a chic staple for work or weekend wear. This blouse features a delicate floral print on lightweight fabric for a soft and romantic look. The relaxed fit and button-front design make it easy to style, while the long sleeves can be rolled up for a more casual vibe. Pair with jeans for a laid-back ensemble or tuck into a pencil skirt for a polished office look.", 18, 12, 23, 30, 0, 1, 3, 2, 1, 1),
    (13, "Forever 21 Crop Top and Shorts Set - Tie-Dye", "Channel laid-back vibes with the Forever 21 crop top and shorts set, perfect for lounging or casual outings. This set features a trendy tie-dye print in vibrant colors for a playful look. The crop top has a relaxed fit with short sleeves and a crew neckline, while the shorts have an elasticized waistband for a comfortable fit. Made from soft and lightweight fabric, this matching set offers effortless style and all-day comfort. Pair with sneakers for a sporty-chic ensemble or sandals for a relaxed summer look.", 6, 13, 25, 45, 0, 1, 3, 3, 1, 1),
    (14, "Mango Midi Dress - Striped", "Elevate your summer wardrobe with the Mango midi dress, a breezy staple that exudes effortless elegance. This dress features a timeless striped pattern in navy and white for a nautical-inspired look. The relaxed silhouette is complemented by a V-neckline, short sleeves, and side slit details for added style. Made from lightweight fabric with a soft, flowy drape, this dress is perfect for warm-weather days. Whether you're strolling along the beach or attending a backyard barbecue, the Mango midi dress offers chic simplicity for any occasion.", 9, 14, 27, 70, 0, 1, 3, 4, 1, 1),
    (15, "Levi's 501 Original Fit Men's Jeans - Stonewash", "Stay true to classic denim style with the Levi's 501 Original Fit men's jeans, a timeless favorite for every wardrobe. These jeans feature a straight leg silhouette with a button fly and signature Levi's tab on the back pocket. The stonewash finish adds a lived-in look with subtle fading and whiskering for a vintage vibe. Made from durable denim with a hint of stretch, these jeans offer all-day comfort and lasting wear. Whether dressed up with a button-down shirt or kept casual with a T-shirt, the Levi's 501 jeans are a versatile essential for any occasion.", 11, 15, 29, 80, 0, 1, 1, 5, 1, 1),
    (16, "Tommy Hilfiger Trucker Jacket - Blue", "Add a touch of Americana to your wardrobe with the Tommy Hilfiger Trucker jacket, a classic staple inspired by vintage denim. This jacket features a timeless trucker silhouette with a pointed collar, button front closure, and adjustable waist tabs for a custom fit. The medium blue wash and contrast stitching lend a retro vibe, while the cotton denim construction offers durability and comfort. Whether layered over a hoodie for a laid-back look or paired with chinos for a more polished ensemble, the Tommy Hilfiger Trucker jacket is a versatile layering piece for any season.", 14, 16, 30, 120, 0, 1, 1, 6, 1, 1),
    (17, "Calvin Klein Polo Shirt - Black", "Upgrade your casual wardrobe with the Calvin Klein polo shirt, a versatile staple that combines comfort and style. This polo shirt features a classic black hue with a contrasting Calvin Klein logo embroidered at the chest for a signature touch. The cotton piqué fabric offers a soft and breathable feel, while the ribbed collar and cuffs add a polished finish. Whether paired with jeans for a relaxed weekend look or dressed up with chinos for a smart-casual ensemble, the Calvin Klein polo shirt is a timeless essential for any occasion.", 16, 17, 32, 45, 0, 1, 1, 7, 1, 1),
    (18, "Ralph Lauren Chino Pants - Khaki", "Step up your casual style with the Ralph Lauren chino pants, a wardrobe staple for the modern gentleman. These pants feature a classic straight fit with a mid-rise waist and flat front for a polished look. The cotton twill fabric offers comfort and durability, while the versatile khaki hue pairs effortlessly with a variety of tops and shoes. Whether worn with a button-down shirt for a business-casual look or dressed down with a T-shirt for weekend outings, the Ralph Lauren chino pants deliver timeless sophistication for any occasion.", 18, 18, 36, 90, 0, 1, 1, 8, 1, 1),
    (19, "Carter's Baby Bodysuit Set - Assorted Colors", "Keep your little one cute and cozy with the Carter's baby bodysuit set, a must-have for every newborn's wardrobe. This set includes five bodysuits in assorted colors and prints, perfect for mixing and matching with any outfit. Made from soft cotton with nickel-free snaps at the bottom, these bodysuits are gentle on your baby's delicate skin and easy to dress. Whether worn alone on warm days or layered under outfits for added warmth, the Carter's baby bodysuit set offers comfort and convenience for busy parents.", 20, 19, 37, 25, 0, 1, 9, 9, 1, 1),
    (20, "Gap Kids Zip Hoodie - Heather Grey", "Keep your little one cozy and stylish with the Gap Kids zip hoodie, a versatile layering piece for any season. This hoodie features a classic heather grey hue with a full-length zipper closure and split kangaroo pocket for added convenience. The soft cotton-blend fabric offers warmth and comfort, while the ribbed cuffs and hem ensure a snug fit. Whether worn over a T-shirt for casual outings or layered under a jacket for extra warmth, the Gap Kids zip hoodie is a wardrobe essential for active kids.", 21, 20, 39, 35, 0, 1, 9, 9, 2, 1),
    (21, "Old Navy Girls' Bodysuit - Pink Floral", "Dress your little one in sweet style with the Old Navy girls' bodysuit, a charming addition to her wardrobe. This bodysuit features an allover pink floral print for a playful look, with ruffled sleeves and a bow detail for added flair. The snap closures at the bottom make dressing and changing easy, while the soft cotton fabric ensures comfort throughout the day. Whether paired with leggings for a cute and casual ensemble or layered under a skirt for special occasions, the Old Navy girls' bodysuit is sure to delight.", 22, 21, 41, 15, 0, 1, 9, 9, 3, 1),
    (22, "H&M Baby Onesie - Striped", "Keep your little one comfy and cute with the H&M baby onesie, a versatile staple for everyday wear. This onesie features a classic striped pattern in neutral hues for a timeless look, with long sleeves and snap closures for easy dressing. The soft cotton fabric is gentle on delicate skin, while the ribbed cuffs ensure a snug fit. Whether worn alone on warmer days or layered under clothes for added warmth, the H&M baby onesie is an essential addition to your baby's wardrobe.", 18, 22, 43, 20, 0, 1, 9, 9, 4, 1),
    (23, "Gucci Soho Disco Leather Bag - Red", "Elevate your accessory game with the Gucci Soho Disco leather bag, a chic and versatile piece that exudes luxury. Crafted in Italy from supple red leather, this crossbody bag features the iconic embossed GG logo and a tassel zipper pull for a touch of sophistication. The compact size is perfect for carrying your essentials, while the adjustable shoulder strap offers customizable wear. Whether paired with casual denim or dressed up for a night out, the Gucci Soho Disco bag adds a pop of color and style to any ensemble.", 7, 8, 16, 1200, 0, 1, 2, 5, 1, 1),
    (24, "Louis Vuitton Speedy Bandouliere 30 Bag - Damier Ebene", "Make a statement with the Louis Vuitton Speedy Bandouliere 30 bag, a timeless classic that combines elegance and functionality. Crafted from signature Damier Ebene canvas with smooth leather trim, this iconic bag features rolled top handles, a detachable shoulder strap, and a spacious interior with a zip pocket and two flat pockets. The golden brass hardware adds a luxurious touch, while the zip closure keeps your belongings secure. Whether carried by hand, on the shoulder, or crossbody, the Louis Vuitton Speedy Bandouliere 30 bag is the epitome of chic sophistication.", 5, 9, 18, 1700, 0, 1, 2, 6, 1, 1),
    (25, "Prada Cahier Leather Shoulder Bag - Black", "Add a touch of edge to your look with the Prada Cahier leather shoulder bag, a statement piece that exudes modern glamour. Crafted in Italy from smooth black leather, this structured bag features gold-tone hardware, including a decorative metal plate and buckle closure inspired by antique books. The adjustable chain and leather shoulder strap offer versatile wear, while the interior compartments keep your essentials organized. Whether paired with casual denim or evening attire, the Prada Cahier shoulder bag adds a bold finishing touch to any outfit.", 3, 10, 20, 2200, 0, 1, 2, 7, 1, 1),
    (26, "Zara Skinny Jeans - Dark Wash", "Achieve effortless style with the Zara skinny jeans, a wardrobe staple that pairs perfectly with everything from T-shirts to blouses. These jeans feature a flattering dark wash with subtle fading and whiskering for a worn-in look. The skinny fit hugs your curves for a sleek silhouette, while the stretch denim offers comfort and flexibility. Whether dressed up with heels or kept casual with sneakers, the Zara skinny jeans are a versatile essential for any occasion.", 13, 22, 22, 50, 0, 1, 3, 5, 1, 1),
    (27, "H&M Sweater - Cable Knit", "Stay cozy and stylish with the H&M cable knit sweater, a cold-weather essential for every wardrobe. This sweater features a classic cable knit design with ribbed trim at the neckline, cuffs, and hem for a timeless look. The relaxed fit and soft yarn make it perfect for layering over shirts or wearing on its own, while the versatile color pairs effortlessly with jeans or trousers. Whether lounging at home or running errands in town, the H&M cable knit sweater keeps you warm and chic all season long.", 20, 24, 24, 40, 0, 1, 3, 6, 1, 1),
    (28, "Forever 21 Jumpsuit - Floral Print", "Make a statement with the Forever 21 jumpsuit, a chic and versatile piece for any occasion. This jumpsuit features a vibrant floral print on lightweight fabric for a breezy, feminine look. The V-neckline and tie waist flatter your figure, while the wide legs create a relaxed silhouette. With adjustable spaghetti straps and side pockets for added convenience, this jumpsuit is perfect for day-to-night wear. Whether paired with sandals for a casual outing or dressed up with heels for a special event, the Forever 21 jumpsuit adds effortless style to your wardrobe.", 10, 27, 28, 60, 0, 1, 3, 8, 1, 1),
    (29, "Levi's Trucker Jacket - Black", "Add a timeless layer to your look with the Levi's trucker jacket, a classic staple for every wardrobe. This jacket features a sleek black wash with contrast stitching for a modern edge. The classic trucker silhouette is complemented by a pointed collar, button front closure, and adjustable waist tabs for a customized fit. Made from durable denim with a hint of stretch, this jacket offers comfort and style in equal measure. Whether worn over a T-shirt for a casual vibe or layered over a dress for a more polished look, the Levi's trucker jacket is a versatile piece for any season.", 6, 16, 31, 90, 0, 1, 1, 9, 1, 1),
    (30, "Tommy Hilfiger Polo Shirt - Navy", "Upgrade your casual wardrobe with the Tommy Hilfiger polo shirt, a timeless essential for every man. This polo shirt features a classic navy hue with contrasting white accents for a sporty look. The embroidered Tommy Hilfiger flag logo adds a signature touch, while the ribbed collar and cuffs offer a polished finish. Made from soft cotton piqué fabric, this shirt provides breathable comfort all day long. Whether paired with jeans for a laid-back weekend or chinos for a smart-casual look, the Tommy Hilfiger polo shirt is a versatile staple for any occasion.", 17, 17, 33, 60, 0, 1, 1, 10, 1, 1),
    (31, "Calvin Klein Boxer Briefs - Pack of 3", "Experience all-day comfort with the Calvin Klein boxer briefs, a must-have addition to your underwear collection. This pack includes three pairs of boxer briefs in classic colors, featuring the iconic Calvin Klein logo waistband for a stylish touch. The stretch cotton fabric provides a snug fit and retains its shape wash after wash, while the contoured pouch offers support and freedom of movement. Whether worn under jeans or loungewear, the Calvin Klein boxer briefs deliver unbeatable comfort and style.", 9, 17, 34, 35, 0, 1, 1, 11, 1, 1),
    (32, "Ralph Lauren Polo Shirt - White", "Stay cool and stylish with the Ralph Lauren polo shirt, a timeless classic for every wardrobe. This shirt features a crisp white hue with the signature Ralph Lauren pony logo embroidered at the chest for a preppy touch. The ribbed collar and armbands add a sporty vibe, while the breathable cotton mesh fabric keeps you comfortable all day long. Whether paired with jeans for a casual look or dressed up with chinos for a more refined ensemble, the Ralph Lauren polo shirt is a versatile essential for any occasion.", 8, 17, 35, 60, 0, 1, 1, 12, 1, 1),
    (33, "Carter's Footed Pajamas - Animal Print", "Keep your little one cozy and cute with Carter's footed pajamas, perfect for bedtime and lounging at home. These pajamas feature an adorable animal print in soft, breathable fabric for a comfortable night's sleep. The footed design keeps tiny toes warm, while the full-length zipper makes dressing and diaper changes a breeze. Whether worn for naptime or nighttime, Carter's footed pajamas are a must-have for your baby's sleepwear collection.", 13, 19, 38, 25, 0, 1, 9, 9, 5, 1),
    (34, "Gap Cargo Pants - Olive Green", "Update your casual wardrobe with Gap cargo pants, a versatile option for everyday wear. These pants feature a classic olive green hue with cargo pockets for a utilitarian-inspired look. The straight fit and mid-rise waist offer a comfortable and flattering silhouette, while the adjustable drawstring cuffs add a touch of sporty style. Made from durable cotton twill, these pants are perfect for pairing with T-shirts and sneakers for a laid-back ensemble.", 12, 20, 40, 70, 0, 1, 3, 6, 2, 1),
    (35, "Zara Bodysuit - Ribbed Black", "Add a touch of sleekness to your wardrobe with the Zara bodysuit, a versatile piece that pairs effortlessly with skirts, jeans, and more. This bodysuit features a ribbed knit construction in classic black for a timeless look. The scoop neckline and slim fit create a flattering silhouette, while the snap closure at the crotch ensures a secure fit. Whether worn alone for a streamlined look or layered under jackets and cardigans for added warmth, the Zara bodysuit is a chic addition to any outfit.", 16, 21, 41, 30, 0, 1, 3, 7, 1, 1),
    (36, "H&M Leggings - Navy Blue", "Stay comfortable and stylish with H&M leggings, a versatile staple for every wardrobe. These leggings feature a rich navy blue hue and a stretchy, form-fitting silhouette for a flattering look and all-day comfort. The elasticized waistband ensures a secure fit, while the soft, breathable fabric moves with you throughout the day. Whether paired with oversized sweaters for a cozy ensemble or layered under dresses and tunics for added coverage, H&M leggings are a wardrobe essential for any season.", 14, 22, 44, 20, 0, 1, 3, 8, 2, 1),
    (37, "Converse Chuck 70 High Top Sneakers - Red", "Make a bold statement with Converse Chuck 70 high top sneakers, a classic silhouette updated in vibrant red. These sneakers feature a durable canvas upper with the iconic Chuck Taylor patch on the ankle for a timeless look. The high top design provides ankle support and protection, while the cushioned footbed offers comfort for all-day wear. With a rubber toe cap and outsole for durability and traction, these Converse Chuck 70 sneakers are perfect for adding a pop of color to any outfit.", 10, 4, 8, 80, 0, 1, 1, 13, 1, 1),
    (38, "Vans Sk8-Hi MTE Shoes - Black/Black", "Stay warm and stylish in colder weather with Vans Sk8-Hi MTE shoes, a winterized version of the classic high top sneaker. These shoes feature a Scotchgard-treated leather upper to repel water and keep your feet dry, while the fleece lining provides warmth and insulation. The padded collar offers support and flexibility, while the vulcanized lug outsole delivers traction on slippery surfaces. Whether braving the elements or hitting the streets, Vans Sk8-Hi MTE shoes keep you looking cool while staying warm and dry.", 15, 5, 10, 110, 0, 1, 1, 14, 1, 1),
    (39, "New Balance 990v5 Men's Running Shoes - Grey/Navy", "Step up your running game with New Balance 990v5 men's running shoes, a comfortable and supportive option for your daily workouts. These shoes feature a breathable mesh and suede upper in a stylish grey and navy colorway. The ENCAP midsole cushioning and ABZORB heel provide superior shock absorption and stability, while the rubber outsole offers traction on various surfaces. Whether hitting the track or pounding the pavement, New Balance 990v5 running shoes deliver the performance you need and the style you want.", 9, 6, 12, 150, 0, 1, 1, 15, 1, 1),
    (40, "Ray-Ban Aviator Sunglasses - Gold/Green Classic", "Add a touch of timeless style to your look with Ray-Ban Aviator sunglasses, a classic accessory that never goes out of fashion. These sunglasses feature a gold-tone metal frame with green classic G-15 lenses for a sleek and sophisticated look. The double bridge and adjustable nose pads ensure a comfortable fit, while the teardrop-shaped lenses provide full coverage and UV protection. Whether lounging on the beach or cruising in the city, Ray-Ban Aviator sunglasses add a dose of cool to any outfit.", 20, 7, 14, 180, 0, 1, 2, 2, 1, 1);

-- Insert data into ImageItem table
INSERT INTO ImageItem (ItemId, ImageId)
VALUES
    (1, 1),
    (2, 2),
    (3, 2);

-- Insert data into UserReview table
INSERT INTO UserReview (ReviewerId, ReviewedId, StarsNumber, Description)
VALUES
    (1, 2, 4, 'Great buyer, smooth transaction'),
    (2, 1, 5, 'Excellent seller, item as described');

-- Insert data into ItemReview table
INSERT INTO ItemReview (ReviewerId, ReviewedId, StarsNumber, Description)
VALUES
    (1, 3, 4, 'Nice phone, works perfectly.'),
    (2, 1, 5, 'Great shoes, very comfortable.'),
    (1, 2, 3, 'Good quality, but packaging could be better.'),
    (2, 3, 4, 'Excellent tablet, fast performance.');

-- Insert data into Chat table
INSERT INTO Chat (ChatId, SellerId, BuyerId, BuyerMoney, SellerMoney)
VALUES
    (1, 1, 2, 500, 0),
    (2, 2, 1, 0, 300);

-- Insert data into BuyerProductsOffered table
INSERT INTO BuyerProductsOffered (ChatId, ProductId, Quantity)
VALUES
    (1, 3, 1),
    (2, 1, 1);

-- Insert data into SellerProductsOffered table
INSERT INTO SellerProductsOffered (ChatId, ProductId, Quantity)
VALUES
    (1, 1, 1),
    (2, 3, 1);

-- Insert data into Message table
INSERT INTO Message (MessageId, MessageTime, MessageText, MessengerId, ChatId)
VALUES
    (1, '2024-05-03 10:30:00', 'Hi there!', 1, 1),
    (2, '2024-05-03 11:00:00', 'Hello!', 2, 1),
    (3, '2024-05-04 09:00:00', 'Interested in your product.', 2, 2),
    (4, '2024-05-04 09:15:00', 'Sure, let me know if you have any questions.', 1, 2);