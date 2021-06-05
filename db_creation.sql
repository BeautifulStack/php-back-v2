CREATE TABLE `User`
(
 `idUser`           integer NOT NULL AUTO_INCREMENT ,
 `isAdmin`          integer NOT NULL ,
 `firstname`        varchar(100) NOT NULL ,
 `lastname`         varchar(100) NOT NULL ,
 `email`            varchar(255) NOT NULL ,
 `phonenumber`      varchar(15) NOT NULL ,
 `inscriptionDate`  timestamp NOT NULL ,
 `lastlogin`        timestamp NOT NULL ,
 `isValidated`      integer NOT NULL ,
 `password`         varchar(255) NOT NULL ,
 `verificationCode` varchar(255) NOT NULL ,
 `publicKey`        varchar(255) NOT NULL ,

PRIMARY KEY (`idUser`)
);


CREATE TABLE `warehouse`
(
 `idWarehouse` integer NOT NULL AUTO_INCREMENT ,
 `maxCapacity` integer NOT NULL ,
 `location`    varchar(255) NOT NULL ,
 `addresse`    varchar(255) NOT NULL ,
 `publicKey`   varchar(255) NOT NULL ,

PRIMARY KEY (`idWarehouse`)
);


CREATE TABLE `brand`
(
 `idBrand`   integer NOT NULL AUTO_INCREMENT ,
 `brandName` varchar(45) NOT NULL ,
 `brandLogo` varchar(255) NOT NULL ,

PRIMARY KEY (`idBrand`)
);

CREATE TABLE `category`
(
 `idCategory`   integer NOT NULL AUTO_INCREMENT ,
 `categoryName` varchar(255) NOT NULL ,

PRIMARY KEY (`idCategory`)
);

CREATE TABLE `sell`
(
 `idSell`          integer NOT NULL AUTO_INCREMENT ,
 `dateProposition` timestamp NOT NULL ,
 `idUser`          integer NOT NULL ,

PRIMARY KEY (`idSell`),
KEY `fkIdx_51` (`idUser`),
CONSTRAINT `FK_50` FOREIGN KEY `fkIdx_51` (`idUser`) REFERENCES `User` (`idUser`)
);



CREATE TABLE `model`
(
 `idModel`       integer NOT NULL AUTO_INCREMENT ,
 `idBrand`       integer NOT NULL ,
 `idCategory`    integer NOT NULL ,
 `originalPrice` float NOT NULL ,
 `resellPrice`   float NOT NULL ,

PRIMARY KEY (`idModel`),
KEY `fkIdx_66` (`idBrand`),
CONSTRAINT `FK_65` FOREIGN KEY `fkIdx_66` (`idBrand`) REFERENCES `brand` (`idBrand`),
KEY `fkIdx_69` (`idCategory`),
CONSTRAINT `FK_68` FOREIGN KEY `fkIdx_69` (`idCategory`) REFERENCES `category` (`idCategory`)
);

CREATE TABLE `sellPossibilities`
(
 `idPossibilities`   integer NOT NULL AUTO_INCREMENT ,
 `possibilityName`   varchar(45) NOT NULL ,
 `possibilityValues` text NOT NULL ,
 `idModel`           integer NOT NULL ,

PRIMARY KEY (`idPossibilities`),
KEY `fkIdx_118` (`idModel`),
CONSTRAINT `FK_117` FOREIGN KEY `fkIdx_118` (`idModel`) REFERENCES `model` (`idModel`)
);

    CREATE TABLE `offer`
    (
    `idOffer`      integer NOT NULL AUTO_INCREMENT ,
    `idSell`       integer NOT NULL ,
    `idUser`       integer NOT NULL ,
    `price`        float NOT NULL ,
    `comment`      text NOT NULL ,
    `productState` enum('good', 'ok','bad') NOT NULL ,
    `idModel`      integer NOT NULL ,
    `proposedBy`   integer NOT NULL ,
    `status`       enum('accept','deny','counter') NOT NULL ,
    `order`        integer NOT NULL ,

    PRIMARY KEY (`idOffer`),
    KEY `fkIdx_47` (`idSell`),
    CONSTRAINT `FK_46` FOREIGN KEY `fkIdx_47` (`idSell`) REFERENCES `sell` (`idSell`),
    KEY `fkIdx_54` (`idUser`),
    CONSTRAINT `FK_53` FOREIGN KEY `fkIdx_54` (`idUser`) REFERENCES `User` (`idUser`),
    KEY `fkIdx_73` (`idModel`),
    CONSTRAINT `FK_72` FOREIGN KEY `fkIdx_73` (`idModel`) REFERENCES `model` (`idModel`)
    );

CREATE TABLE `buy`
(
 `idBuy`           integer NOT NULL AUTO_INCREMENT ,
 `date`            timestamp NOT NULL ,
 `idUser`          integer NOT NULL ,
 `totalPrice`      float NOT NULL ,
 `shippingAddress` varchar(255) NOT NULL ,
 `payementStatus`  enum('accepted','denied') NOT NULL ,

PRIMARY KEY (`idBuy`),
KEY `fkIdx_80` (`idUser`),
CONSTRAINT `FK_79` FOREIGN KEY `fkIdx_80` (`idUser`) REFERENCES `User` (`idUser`)
);

CREATE TABLE `caract`
(
 `idCaract`    integer NOT NULL AUTO_INCREMENT ,
 `idModel`     integer NOT NULL ,
 `caractName`  varchar(45) NOT NULL ,
 `caractValue` varchar(45) NOT NULL ,

PRIMARY KEY (`idCaract`),
KEY `fkIdx_108` (`idModel`),
CONSTRAINT `FK_107` FOREIGN KEY `fkIdx_108` (`idModel`) REFERENCES `model` (`idModel`)
);

CREATE TABLE `buyedProducts`
(
 `idBuy`     integer NOT NULL ,
 `price`     float NOT NULL ,
 `idProduct` integer NOT NULL ,

PRIMARY KEY (`idBuy`, `idProduct`),
KEY `fkIdx_100` (`idProduct`),
CONSTRAINT `FK_99` FOREIGN KEY `fkIdx_100` (`idProduct`) REFERENCES `product` (`idProduct`),
KEY `fkIdx_96` (`idBuy`),
CONSTRAINT `FK_95` FOREIGN KEY `fkIdx_96` (`idBuy`) REFERENCES `buy` (`idBuy`)
);

CREATE TABLE `InCart`
(
 `idUser`    integer NOT NULL ,
 `idProduct` integer NOT NULL ,

PRIMARY KEY (`idUser`, `idProduct`),
KEY `fkIdx_135` (`idUser`),
CONSTRAINT `FK_134` FOREIGN KEY `fkIdx_135` (`idUser`) REFERENCES `User` (`idUser`),
KEY `fkIdx_139` (`idProduct`),
CONSTRAINT `FK_138` FOREIGN KEY `fkIdx_139` (`idProduct`) REFERENCES `product` (`idProduct`)
);

CREATE TABLE `offerResponses`
(
 `idOfferResponses` integer NOT NULL AUTO_INCREMENT ,
 `idOffer`          integer NOT NULL ,
 `idPossibilities`  integer NOT NULL ,
 `value`            text NOT NULL ,

PRIMARY KEY (`idOfferResponses`),
KEY `fkIdx_124` (`idOffer`),
CONSTRAINT `FK_123` FOREIGN KEY `fkIdx_124` (`idOffer`) REFERENCES `offer` (`idOffer`),
KEY `fkIdx_127` (`idPossibilities`),
CONSTRAINT `FK_126` FOREIGN KEY `fkIdx_127` (`idPossibilities`) REFERENCES `sellPossibilities` (`idPossibilities`)
);

CREATE TABLE `product`
(
 `idProduct`   integer NOT NULL AUTO_INCREMENT ,
 `idModel`     integer NOT NULL ,
 `idWarehouse` integer NOT NULL ,
 `status`      enum('available','notavailable') NOT NULL ,

PRIMARY KEY (`idProduct`),
KEY `fkIdx_86` (`idModel`),
CONSTRAINT `FK_85` FOREIGN KEY `fkIdx_86` (`idModel`) REFERENCES `model` (`idModel`),
KEY `fkIdx_89` (`idWarehouse`),
CONSTRAINT `FK_88` FOREIGN KEY `fkIdx_89` (`idWarehouse`) REFERENCES `warehouse` (`idWarehouse`)
);