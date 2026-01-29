-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 16, 2024 at 05:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mat0`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `AdminId` int(11) NOT NULL,
  `FirstName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `DateJoined` date DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`AdminId`, `FirstName`, `LastName`, `Email`, `ContactNumber`, `DateJoined`, `password`) VALUES
(1, 'Kyn', 'Honoridez', 'kyn.honoridez@example.com', '09171234567', '2020-01-01', 'KYN'),
(2, 'Al Winmon', 'Montebon', 'al.montebon@example.com', '09276543210', '2020-02-01', 'MON'),
(3, 'Daniel Ryan', 'So', 'daniel.so@example.com', '09345678901', '2020-03-01', 'SO'),
(0, NULL, NULL, NULL, NULL, NULL, 'KYN');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(50) NOT NULL,
  `PICTURES` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`, `PICTURES`) VALUES
(1, 'CEMENT AND CONCRETE', 'PICTURES/CATEGORIES/PICTURES/1.png'),
(2, 'ELECTRICAL', 'PICTURES/CATEGORIES/PICTURES/2.png'),
(3, 'TOOLS AND EQUIPMENTS', 'PICTURES/CATEGORIES/PICTURES/3.png'),
(4, 'WOOD AND TIMBER', 'PICTURES/CATEGORIES/PICTURES/4.png'),
(5, 'INSULATION MATERIAL', 'PICTURES/CATEGORIES/PICTURES/5.png'),
(6, 'STEEL', 'PICTURES/CATEGORIES/PICTURES/6.png'),
(7, 'PLUMBING', 'PICTURES/CATEGORIES/PICTURES/7.png'),
(8, 'ROOFING MATERIALS', 'PICTURES/CATEGORIES/PICTURES/8.png'),
(9, 'FLOORING AND TILES', 'PICTURES/CATEGORIES/PICTURES/9.png'),
(10, 'PAINT AND COATINGS', 'PICTURES/CATEGORIES/PICTURES/10.png'),
(11, 'GLASS AND GLAZING', 'PICTURES/CATEGORIES/PICTURES/11.png'),
(12, 'FASTENER AND ADHESIVES', 'PICTURES/CATEGORIES/PICTURES/12.png'),
(13, 'DOORS AND WINDOWS', 'PICTURES/CATEGORIES/PICTURES/13.png'),
(14, 'SAFETY AND PROTECTIVE GEAR', 'PICTURES/CATEGORIES/PICTURES/14.png'),
(15, 'HVAC', 'PICTURES/CATEGORIES/PICTURES/15.png');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `CustomerID` int(11) NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `LastName` varchar(50) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `current_location` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`CustomerID`, `FirstName`, `LastName`, `Email`, `PhoneNumber`, `Username`, `Password`, `date_of_birth`, `current_location`, `profile_picture`) VALUES
(1, 'Hello', 'World', 'helloworld@example.com', '123-456-7890', 'helloworld', 'hello', '1990-02-12', 'America', 'PP/1.png'),
(3, 'guys', 'my', 'name@name', '123', 'hi', '123', '1985-06-25', 'Los Angeles', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orderitems`
--

CREATE TABLE `orderitems` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `CustomerID` int(11) DEFAULT NULL,
  `ostatus` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orderitems`
--

INSERT INTO `orderitems` (`order_item_id`, `order_id`, `product_id`, `quantity`, `unit_price`, `CustomerID`, `ostatus`) VALUES
(15, 24, 10, 1, 229.75, 3, 'Declined'),
(47, 17, 41, 1, 3490.00, 1, 'Declined'),
(48, 18, 30, 1, 148.00, 3, 'Declined'),
(49, 18, 65, 1, 5850.00, 3, 'completed'),
(50, 18, 57, 1, 110.00, 3, 'completed'),
(51, 19, 48, 5, 198.00, 3, 'completed'),
(52, 20, 42, 5, 3370.00, 1, 'completed'),
(53, 21, 41, 1, 3490.00, 1, 'completed'),
(54, 22, 46, 1, 3130.00, 1, 'completed'),
(55, 23, 62, 1, 1850.00, 3, 'Declined'),
(56, 23, 11, 1, 5125.00, 3, 'Declined'),
(57, 24, 39, 1, 1150.00, 3, 'Declined'),
(58, 25, 62, 1, 1850.00, 1, 'Declined'),
(59, 25, 18, 1, 1140.00, 1, 'Declined'),
(60, 26, 67, 1, 155.00, 1, 'completed'),
(61, 26, 47, 1, 470.00, 1, 'completed'),
(62, 27, 59, 1, 240.00, 1, 'completed'),
(63, 27, 46, 1, 3130.00, 1, 'completed'),
(64, 28, 64, 1, 7361.00, 1, 'Declined'),
(65, 28, 15, 1, 499.75, 1, 'completed'),
(66, 29, 48, 1, 198.00, 1, 'Declined'),
(67, 29, 12, 1, 4560.00, 1, 'Declined'),
(68, 30, 6, 1, 14.25, 1, 'completed'),
(69, 30, 47, 1, 470.00, 1, 'Declined'),
(70, 31, 70, 1, 110.00, 1, 'Declined'),
(71, 31, 47, 1, 470.00, 1, 'completed'),
(72, 32, 19, 1, 180.00, 1, 'completed'),
(73, 32, 2, 1, 263.00, 1, 'completed'),
(74, 34, 40, 1, 5500.00, 3, 'Declined'),
(75, 37, 49, 1, 2646.00, 3, 'Declined'),
(76, 34, 9, 1, 29.75, 3, 'completed'),
(77, 39, 29, 1, 62.00, 1, 'completed'),
(78, 40, 48, 1, 198.00, 1, 'completed'),
(79, 33, 27, 1, 12061.00, 3, 'completed'),
(80, 33, 72, 1, 1780.00, 3, 'Declined'),
(81, 35, 71, 1, 42.00, 3, 'completed'),
(82, 35, 47, 1, 470.00, 3, 'Declined'),
(83, 37, 13, 1, 572.00, 3, 'completed'),
(84, 36, 48, 1, 198.00, 3, 'Declined'),
(85, 38, 31, 1, 373.50, 3, 'ordered'),
(86, 36, 14, 1, 610.50, 3, 'completed'),
(87, 38, 41, 1, 3490.00, 3, 'completed'),
(88, 39, 59, 1, 240.00, 1, 'completed'),
(89, 40, 35, 1, 65.00, 1, 'completed'),
(90, 41, 60, 1, 62.50, 1, 'completed'),
(91, 42, 18, 1, 1140.00, 1, 'ordered'),
(97, 43, 51, 1, 725.00, 1, 'completed'),
(98, NULL, 14, 1, NULL, 3, 'cart'),
(99, 43, 75, 1, 11500.00, 1, 'completed'),
(100, 44, 29, 50, 62.00, 1, 'completed'),
(101, 45, 15, 1, 499.75, 1, 'completed'),
(102, 46, 48, 1, 198.00, 1, 'completed'),
(103, 46, 53, 1, 1089.00, 1, 'completed'),
(104, 46, 2, 1, 263.00, 1, 'completed'),
(105, 47, 28, 1, 165.00, 1, 'completed'),
(106, 48, 52, 1, 1122.00, 1, 'completed'),
(107, 49, 14, 1, 610.50, 1, 'completed'),
(108, 50, 2, 1, 263.00, 1, 'completed'),
(115, 51, 76, 1, 14999.00, 1, 'Accepted'),
(116, 52, 53, 1, 1089.00, 1, 'Accepted'),
(117, 53, 56, 1, 5775.00, 1, 'Accepted');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `order_status` varchar(50) DEFAULT NULL,
  `order_total` decimal(10,2) DEFAULT NULL,
  `reference_number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `customer_id`, `order_date`, `order_status`, `order_total`, `reference_number`) VALUES
(40, 1, '2024-07-13', 'Delivering', 77267.50, 'REF1-40-669230101465'),
(41, 1, '2024-07-13', 'Delivering', 77330.00, 'REF1-41-6692318693dd'),
(42, 1, '2024-07-13', 'pending', 49908.50, 'REF1-42-66923ca48a79'),
(43, 1, '2024-07-14', 'Partially Fulfilled', 62507.00, 'REF1-43-6693e6d7f0e5'),
(44, 1, '2024-07-14', 'Delivering', 65607.00, 'REF1-44-6693e9da43cf'),
(45, 1, '2024-07-14', 'Delivering', 66106.75, 'REF1-45-6693f0ab791e'),
(46, 1, '2024-07-15', 'Partially Fulfilled', 67656.75, 'REF1-46-6693f76b8e66'),
(47, 1, '2024-07-15', 'Delivering', 67821.75, 'REF1-47-66948a5bbb54'),
(48, 1, '2024-07-15', 'Delivering', 68943.75, 'REF1-48-6694b449ecfd'),
(49, 1, '2024-07-15', 'Delivering', 69554.25, 'REF1-49-6694b4bdb926'),
(50, 1, '2024-07-15', 'Delivering', 69817.25, 'REF1-50-6694bbba1c07'),
(51, 1, '2024-07-16', 'Delivering', 84442.75, 'REF1-51-669683f5622d'),
(52, 1, '2024-07-16', 'Delivering', 85531.75, 'REF1-52-66968e85c07e'),
(53, 1, '2024-07-16', 'Delivering', 91306.75, 'REF1-53-66968e99bb8d');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `StoreID` int(11) DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(11) NOT NULL,
  `Description` text DEFAULT NULL,
  `Picture` varchar(255) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `product_status` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `StoreID`, `Price`, `Quantity`, `Description`, `Picture`, `CategoryID`, `product_status`) VALUES
(1, 'Mabuhay Portland Cement', 1, 242.00, 500, 'Mabuhay Cement – the foundation for success in building projects of all sizes.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C2.png', 1, 'displayed'),
(2, 'Grand Portland Cement', 1, 263.00, 477, 'Trust in the strength of Grand Portland Cement for reliable and durable construction.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C3.png', 1, 'displayed'),
(3, 'Apo Pozzolan Cement', 1, 265.00, 500, 'Top-tier cement option, offering exceptional strength and reliability for construction projects. Tailored to modern building requirements for long-lasting concrete solutions.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C4.png', 1, 'displayed'),
(4, 'Grand Pozzolan Cement', 1, 200.00, 500, 'High-quality cement known for strength and durability, ideal for residential and commercial construction, ensuring reliability in concrete structures.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C5.png', 1, 'displayed'),
(5, 'Apo Portland Cement', 1, 225.00, 500, 'Apo Cement - the premium choice for exceptional strength and reliability in construction.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C1.png', 1, 'displayed'),
(6, 'Emerald PVC Electrical Bushing & Locknot', 1, 14.25, 489, 'Innovative PVC electrical bushing with Locknot feature for secure insulation and protection of wires in various applications.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C6.png', 2, 'displayed'),
(7, 'Koten Circuit Breaker 2P Plug-in 50A', 1, 416.00, 500, 'High-capacity 2-pole circuit breaker with a 50A rating for demanding applications. Quick plug-in installation ensures swift response to overloads and short circuits.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C7.png', 2, 'displayed'),
(8, 'MALCO Downlight Surface White DS0160R', 1, 505.00, 500, 'Experience the perfect blend of modern design and superior illumination with the MALCO DS0160R White Surface Downlight.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C8.png', 2, 'displayed'),
(9, 'Omni WWP-113 3 Gang Plate', 1, 29.75, 500, 'Streamlined 3 Gang Plate for multiple electrical connections.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C9.png', 2, 'displayed'),
(10, 'Omni WPP-602 Weatherproof Cover 1-3 Gang Flat', 1, 229.75, 500, 'Weatherproof cover for 1-3 gang outlets, suitable for outdoor use.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C10.png', 2, 'displayed'),
(11, 'MAXPRO Rotary Hammer 1200W', 1, 5125.00, 500, 'Elevate your drilling experience with the MAXPRO Rotary Hammer 1200W (model MPRH1200/28P). Boasting a robust 1200-watt motor, this rotary hammer is engineered for high-performance drilling on various materials. Ideal for professionals and DIY enthusiasts seeking power and precision.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C21.png', 3, 'displayed'),
(12, 'MAXPRO Circular Saw 1400/185', 1, 4560.00, 489, 'Experience precision and power with the MAXPRO Circular Saw MPCS1400/185. Boasting 1400 watts of cutting-edge technology, this circular saw is a versatile and reliable tool for various cutting applications, making it an essential addition to your toolkit.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C22.png', 3, 'displayed'),
(13, 'KENDO Framing Square Steel 600x400mm', 1, 572.00, 500, 'Ensure accuracy in framing with the KENDO Framing Square, Steel, 600x400mm. Durable and precise, ideal for framing and layout tasks in construction and woodworking projects.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C23.png', 3, 'displayed'),
(14, 'KENDO Aluminum Level Orange 1200mm', 1, 610.50, 495, 'Ensure precise leveling with the KENDO Aluminum Level, 1200mm. Durable and accurate, ideal for achieving straight and level surfaces in construction and DIY projects.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C24.png', 3, 'displayed'),
(15, 'Stanley Tape Tylon 8m', 1, 499.75, 480, 'Measure with precision using the Stanley STHT306568 Tape Tylon 8m. Durable and reliable, it’s the ideal tool for accurate measurements in various applications.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C25.png', 3, 'displayed'),
(16, 'Alaplana Slipstop Evie Haya 23.3x120cm', 1, 588.00, 500, 'Sized at 23.3x120cm, these European Exclusive Partner Brand tiles embody the warmth and texture of timber, making them an excellent choice for your interior decor.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C29.png', 4, 'displayed'),
(17, 'El Molino Irazu Mix 20x60cm', 1, 307.00, 500, 'Measuring 20x60cm, they offer a harmonious mix of shades and textures, creating a dynamic and visually appealing surface.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C30.png', 4, 'displayed'),
(18, 'ADCO WPC Outdoor Wall Panel Bronze 26x219x2900mm', 1, 1140.00, 478, 'This WPC Outdoor Wall Panel Bronze is an elegant finishing touch to any room. This wall panel can be used as added wall insulation or simply a decorative accent.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C35.png', 5, 'displayed'),
(19, 'SAFETY HASP HAUSMANN MP THS911CP 3.5\' STEEL', 2, 180.00, 489, 'A hasp that is used with a padlock and has a slotted plate fitting over the staple to prevent its removal when locked.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C11.png', 6, 'displayed'),
(23, 'TROLLEY HAND TRUCK FOLDABLE TOPLIFT MD TTEF1119-AFT100 100KGS STEEL FRAME', 2, 1750.00, 500, '*Hand Truck *Foldable * Steel Frame Allows you to carry more weight at a time.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C12.png', 6, 'displayed'),
(24, 'BOLT EXPANSION HAUSMANN 20X160MM(3/4\'X6\') STEEL 2\"\"', 2, 376.00, 500, 'carbon steel, zinc plated simple installation and replacement, good anti corrosion performance, new design, anti shake and loose performance, reusable.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C13.png', 6, 'displayed'),
(25, 'RIVET BLIND HAUSMANN 1/8x5/8\' 1000\'s ALU+STEEL ZINC PLATED', 2, 519.00, 500, 'Aluminum rivet, steel mandrel better anti corrosion performance, shiny, light, easy handling, can be applied in many field, such as construction, electronics, and automobile.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C14.png', 6, 'displayed'),
(26, 'BRUSH STEEL TACOMA 379 S/S SOFTGRIP MINI SET', 2, 26.00, 500, 'Steel brush set is suitable for cleaning and descaling rust, paint stains, steel parts, machinery, etc. > The bristle cleaning brush is made of Stainless steel > The handle is made by plastic > Comfortable to use', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C15.png', 6, 'displayed'),
(27, 'Kohler July Series Exposed Bath Shower', 3, 12061.00, 500, 'KOHLER/JULY 7686K-4E2-CHROME PLATED EXPOSED SINGLE LEVER BATH AND SHOWER ECO FLOW POLISHED CHROME', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C16.png', 7, 'displayed'),
(28, 'Pozzi Pvc P- Trap', 3, 165.00, 493, 'POZZI /ACC 1 1/4 PVC P-TRAP W/CLEAN OUT', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C17.png', 7, 'displayed'),
(29, 'Sefa Isaac Pvc Tail Piece', 3, 62.00, -11, 'SEFA/OT OT-01155 ISAAC PVC TAIL PICE 1 1/4 4IN', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C18.png', 7, 'displayed'),
(30, 'Solutherm Water Dispenser Faucet', 3, 148.00, 500, 'SOLUTHERM WDT-34 WATER DISPENSER TAP FAUCET (RED)', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C19.png', 7, 'displayed'),
(31, 'Pozzi Two Angle Valve and Supply Pipe', 3, 373.50, 492, 'POZZI DH8421-2 ANGLE VALVE & SUPPLY PIPE', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C20.png', 7, 'displayed'),
(32, 'Frost King Pre-Slit Pipe Insulation', 3, 81.50, 500, 'FROST KING/ORG P10XB/6 PRE-SLIT PIPE INSULATION 1/2IN.X6FT.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C31.png', 5, 'displayed'),
(33, 'INSULATION SF 10MMX1MX50 1SIDE PE FOAM MTR.', 2, 75.00, 500, 'Foam-in-place insulation can be blown into walls, on attic surfaces, or under floors to insulate and reduce air leakage.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C32.png', 5, 'displayed'),
(34, 'P.Tech Double Bubble/Foil Insulation', 3, 1144.50, 500, 'P.TECH/QTCM A-2 DOUBLE BUBBLE DOUBLE FOIL INSULATION 6MMX1.2', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C33.png', 5, 'displayed'),
(35, 'BUBBLE INSULATION SF 1MX1M SINGLE FOIL ALUM.', 2, 65.00, 489, 'Installations can yield a higher R-value than traditional batt insulation for the same thickness, and can fill even the smallest cavities, creating an effective air barrier.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C34.png', 5, 'displayed'),
(37, 'ROOFING RAVAK .8MMX.93MX2.44M PC CORR. CLEAR', 2, 1390.00, 500, 'ROOFING RAVAK .8MMX.93MX2.44M PC CORR. CLEAR refers to a clear, corrugated polycarbonate roofing sheet. The dimensions are 0.8 mm in thickness, 0.93 meters in width, and 2.44 meters in length.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C36.png', 8, 'displayed'),
(38, 'ROOFING RAVAK 6MMX1.22X4.88M TW PC BROWN', 2, 2595.00, 500, 'Lightweight. High-Quality. UV Resistant.Resistant to Extreme Temperatures. Durable. Versatile. Easy to Install Lighter alternative to glass and a natural UV filter. Easy to clean', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C37.png', 8, 'displayed'),
(39, 'ROOFING RAVAK .8MMX.930X3048MM FB CORR. TL', 2, 1150.00, 500, 'The product \"\"ROOFING RAVAK .8MMX.930X3048MM FB CORR. TL\"\" is a corrugated roofing sheet made from fiber-reinforced plastic. It has a thickness of 0.8 mm, a width of 930 mm, and a length of 3048 mm.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C38.png', 8, 'displayed'),
(40, 'ROOFING RAVAK 3X1220X2440MM PC SOLID BROWN', 2, 5500.00, 500, 'It sounds like you\'re referring to a specific type of roofing material or product description. \"\"RAVAK 3X1220X2440MM PC SOLID BROWN\"\" typically indicates a solid polycarbonate (PC) sheet, brown in color, with dimensions of 1220mm x 2440mm and a thickness of 3mm.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C39.png', 8, 'displayed'),
(41, 'ROOF VENTILATOR SS 430X400X300MM STAINLESS 304', 1, 3490.00, 478, 'Stainless steel grade 304 is a commonly used material for its corrosion resistance, making it suitable for outdoor applications like roof ventilators. The dimensions provided (430x400x300mm) likely refer to the length, width, and height of the ventilator, indicating its size.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C40.png', 8, 'displayed'),
(42, 'PRW SPC Flooring Coastline 4.5x183x1220mm (10pcs/box)', 1, 3370.00, 445, 'These 4.5mm thick, 183mm wide, and 1220mm long planks come in a box of 10, providing a seamless and durable solution for your flooring needs.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C41.png', 9, 'displayed'),
(43, 'Luxe HD KJ360721 30x60cm – Elegant Home Flooring', 1, 77.00, 500, 'This Luxe HD KJ360721 30x60cm tile is a fantastic enhancement for any space.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C42.png', 9, 'displayed'),
(44, 'Fino Mix Blue White and Light Blue 30x30cm', 1, 40.50, 500, 'Fino Mix Blue White and Light Blue 30x30cm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C43.png', 9, 'displayed'),
(45, 'Rialto 2CM STR GRY61A 60x60cm', 1, 699.00, 500, 'Rialto 2CM STR GRY61A 60x60cm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C44.png', 9, 'displayed'),
(46, 'PRW SPC Flooring Hickory 4.5x183x1220mm (10pcs/box)', 1, 3130.00, 478, 'These 4.5mm thick, 183mm wide, and 1220mm long planks, packaged in sets of 10, showcase the bold and distinctive grain of hickory wood.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C45.png', 9, 'displayed'),
(47, 'PAINT DAVIES DV-4460 GAL GLOSS-IT QDE BLACK', 2, 470.00, 467, 'All  purpose alkyd based paint ideal for all types of wood and metal surfaces, wide application in both decorative and protective coatings due to its high gloss, good color retention, outstanding durability', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C46.png', 10, 'displayed'),
(48, 'PAINT BOYSEN QRT ENAMEL FLATWALL WHITE 800', 2, 198.00, 470, 'BOYSEN Flat Wall Enamel #800 is a flat alkyd type paint that provides great durability while being noted as a fast drying paint. It is formulated to give a tough and durable film. It is also used as a primer for enamel coatings. PRINCIPAL USES: For interior and exterior wood surfaces.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C47.png', 10, 'displayed'),
(49, 'PAINT DAVIES DV-400 PAILGLOSS-IT QUICKDRY ENAMEL WHITE', 2, 2646.00, 500, 'all  purpose alkyd based paint ideal for all types of wood and metal surfaces , wide application in both decorative and protective coatings due to its high gloss, good color retention, outstanding durability.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C48.png', 10, 'displayed'),
(50, 'PAINT DAVIES DV-4621 LTR GLOSS-IT QDE IVORY', 2, 151.00, 500, 'all purpose alkyd based paint ideal for all types of wood and metal surfacesl, wide application in both decorative and protective coatings due to its high gloss, good color retention, outstanding durability', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C49.png', 10, 'displayed'),
(51, 'PAINT DAVIES DV-400 GAL GLOSS-IT QUICKDRY ENAMEL WHITE', 2, 725.00, 489, 'all  purpose alkyd based paint ideal for all types of wood and metal surfaces, wide application in both decorative and protective coatings due to its high gloss, good color retention, outstanding durability', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C50.png', 10, 'displayed'),
(52, 'Cool Shelf Glass Single Aluminum Satin', 1, 1122.00, 494, 'The Cool Shelf Glass Single in Satin Aluminum is a modern addition that will enhance the look of your bathroom with its sleek design and smooth, glossy finish.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C51.png', 11, 'displayed'),
(53, 'Koten Circuit Breaker 2P Plug-in 50A', 1, 1089.00, 490, 'This Cool Shelf Glass Single Black will complement your bathroom with its modern style and smooth glossy aluminum finish.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C52.png', 11, 'displayed'),
(54, 'Cool Frosted Shower Enclosure Package 1200mmx1850mm', 1, 11458.00, 500, 'Cool FHS001 Frosted Shower Enclosure Package 6mm 1200mmx1850mm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C53.png', 11, 'displayed'),
(55, 'Carpenter Kentucky Solid Half Glass Lawaan 40mmx60cmx210cm', 1, 5870.00, 500, 'Carpenter Kentucky Solid Half Glass Lawaan 40mmx60cmx210cm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C54.png', 11, 'displayed'),
(56, 'Kitchen Pro Burner Glass (DOMB3022) 482x260mm', 1, 5775.00, 499, 'KP DOM-B302 2-Burner Glass 482x260mm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C55.png', 11, 'displayed'),
(57, 'PARTITION TOILET HAUSMANN ACCS. TPHA1119 30-1 CORNER FASTENER SS304 2\'S', 2, 110.00, 500, 'Steel color, SS304. Fit for thickness 12mm panel. Doesn\'t oxidized, modern.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C56.PNG', 12, 'displayed'),
(58, 'PARTITION TOILET HAUSMANN ACCS. OA-1/JM001 AB CORNER FASTENER NYLON BLACK 2\'s', 2, 75.00, 500, 'Black color, Nylon. Fit for thickness 12mm panel. Doesn\'t rust , oxidized. Light ,classical.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C57.PNG', 12, 'displayed'),
(59, 'PARTITION TOILET HAUSMANN ACCS. TPHA1119 P-13 U CHANNEL ALUM. 12MMX2M', 2, 240.00, 478, 'Steel color, aluminum. Fit for thickness 12mm panel. Instead of corner fastener, looks more clean. Concise and can cover the panel edge.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C58.PNG', 12, 'displayed'),
(60, 'Brad Plastic Dowel 8mm 25pcs/pck', 1, 62.50, 489, 'Upgrade woodworking projects with Brad 8mm Plastic Dowels – 25-pack for reliable jointing.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C59.png', 12, 'displayed'),
(61, 'Rugby Excel 300ml (Bottle)', 1, 87.00, 500, 'RUGBY EXCEL 300ML (BOTTLE)', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C60.png', 12, 'displayed'),
(62, 'DOOR MOULDED DESIGNCRAFT DRAVOTTI 700X2100X35MM 4P HC', 2, 1850.00, 489, '*Elegant & Variety of Styles *Durable & Scratch Resistant *Easy to Install *Water Resistant *Eco Product from Subtitued Wood Based Panel', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C61.png', 13, 'displayed'),
(63, 'DOOR ENGINEERED DESIGNCRAFT AT ENGINEERED MILTON 700X2100X35MM 4P LAOS CHERRY', 2, 4350.00, 500, 'The \"Engineered Designcraft\" door from \"Engineered Milton\" measures 700mm wide by 2100mm high with a thickness of 35mm, featuring a 4-panel design crafted from Laos Cherry wood, renowned for its rich reddish-brown color and fine grain.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C62.png', 13, 'displayed'),
(64, 'DOOR DESIGNCRAFT AP WILSON 80X210 40MM W/P GRANDIS', 2, 7361.00, 489, '*Extremely Sturdy *Modern *Energy efficient insulator *Heavy duty', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C63.png', 13, 'displayed'),
(65, 'Westwood Enginered Door 7P OakVeneer 40mmx90cmx210cm', 1, 5850.00, 500, 'Westwood Enginered Door 7P OakVeneer 40mmx90cmx210cm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C64.png', 13, 'displayed'),
(66, 'Westwood MDF Mel Door Eucalyptus 40mmx90cmx210cm', 1, 3040.00, 500, 'estwood MDF Mel Door Eucalyptus 40mmx90cmx210cm', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C65.png', 13, 'displayed'),
(67, 'Welding Mask Polyprolene Plastic Black', 1, 155.00, 489, 'Ensure welding safety with our black polypropylene plastic Welding Mask – lightweight, adjustable, and UV-resistant.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C66.png', 14, 'displayed'),
(68, 'Safety Vest Neon Green', 1, 150.00, 500, 'High-visibility vest for safety with a touch of flair during work.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C67.png', 14, 'displayed'),
(69, 'Safety Harness Full Body', 1, 910.00, 500, 'Durable and ergonomic design for heightened safety during elevated tasks.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C68.png', 14, 'displayed'),
(70, 'TOPMAN SAFETY GOGGLE BLACK', 1, 110.00, 489, 'Combine style and safety with TOPMAN SAFETY GOGGLE in black. These goggles provide superior eye protection in a sleek and durable design.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C69.png', 14, 'displayed'),
(71, 'TOPMAN SAFETY GLOVE BLUE', 1, 42.00, 500, 'Blue Topman Safety Gloves: Reliable hand protection with comfort and style for various tasks.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C70.png', 14, 'displayed'),
(72, 'MAXPRO Heat Gun 2000W', 1, 1780.00, 500, 'Unlock versatile heating capabilities with the MAXPRO Heat Gun MPHG2001. Delivering 2000 watts of power, this heat gun is a high-performance tool designed for a wide range of applications. From paint stripping to shrink-wrapping, experience efficient and controlled heating for your projects.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C71.png', 15, 'displayed'),
(73, 'Stiebel Eltron IM 60EC Water Heater', 1, 10499.75, 500, 'Experience advanced water heating with the Stiebel Eltron IM 60EC Water Heater. This high-performance electric water heater features cutting-edge technology for efficient heating, providing a reliable and energy-efficient solution for residential and commercial applications.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C72.png', 15, 'displayed'),
(74, 'ADCO Soffit Panel Vent 1.2mmx2.9m', 1, 720.00, 500, 'Ventilated panel for enhanced airflow and modern aesthetics in architectural designs.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C73.png', 15, 'displayed'),
(75, 'Panasonic Press Jet Heater DH-3EP3P', 1, 11500.00, 489, 'Upgrade your water heating system with the Panasonic DH-3EP3P PressJet Heater. This innovative water heater is equipped with PressJet technology, providing a powerful and efficient solution for instant hot water. With a user-friendly design and advanced features, it delivers convenience and comfort to your daily routine.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C74.png', 15, 'displayed'),
(76, 'Grundfos UPA15-90 Circulating Pump 170W', 1, 14999.00, 497, 'Enhance your water circulation system with the Grundfos UPA15-90 Circulating Pump 170W. This high-quality circulating pump from Grundfos, featuring a 170W power rating, ensures efficient water circulation for various residential and commercial applications.', '/IM_2/PICTURES/CATEGORIES/STORES/HOMEBUILDERS/C75.png', 15, 'displayed'),
(77, 'Hammer', 1, 500.00, 52, 'Strong.Durable.', NULL, NULL, 'pending'),
(78, 'Nails', 1, 50.00, 500, 'strong.', NULL, NULL, 'pending'),
(79, 'screwdriver', 1, 50.00, 500, 'strong.', NULL, NULL, 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `StoreID` int(11) NOT NULL,
  `StoreName` varchar(255) NOT NULL,
  `Location` varchar(255) NOT NULL,
  `EmailAddress` varchar(255) NOT NULL,
  `PhoneNumber` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`StoreID`, `StoreName`, `Location`, `EmailAddress`, `PhoneNumber`, `password`) VALUES
(1, 'CEBU HOME BUILDERS', 'A. S. Fortuna St, Mandaue City, 6014 Cebu', 'Homebuilders@sellers.com', '63 230 3777', 'HOME'),
(2, 'CITI HARDWARE', 'Brgy. Tabok, Mandaue City, Cebu', 'CitiHardware@sellers.com', '(0919) 082 7221', 'CITI'),
(3, 'WILCON DEPOT', 'Brgy, U.N. Ave, Mandaue City, Cebu', 'Wilcon@sellers.com', '0322362958', 'WIL');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`CustomerID`);

--
-- Indexes for table `orderitems`
--
ALTER TABLE `orderitems`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_orderitems_customerid` (`CustomerID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD UNIQUE KEY `reference_number` (`reference_number`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `StoreID` (`StoreID`),
  ADD KEY `fk_CategoryID` (`CategoryID`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`StoreID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `CustomerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orderitems`
--
ALTER TABLE `orderitems`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `StoreID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
