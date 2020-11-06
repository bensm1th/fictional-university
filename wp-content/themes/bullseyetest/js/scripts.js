import "../css/style.css"

// Our modules / classes
import MobileMenu from "./modules/MobileMenu";
import HeroSlider from "./modules/HeroSlider";
import GoogleMap from "./modules/GoogleMap";
import LiveSearch from "./modules/Search";
import MyNotes from "./modules/MyNotes";
import Like from "./modules/Like";


// Instantiate a new object using our modules/classes
var mobileMenu = new MobileMenu();
var heroSlider = new HeroSlider();
const googleMap = new GoogleMap();
const magicalSearch = new LiveSearch();
const myNotes = new MyNotes();
const like = new Like();

//minor change

// Allow new JS and CSS to load in browser without a traditional page refresh
if (module.hot) {
  module.hot.accept()
}
