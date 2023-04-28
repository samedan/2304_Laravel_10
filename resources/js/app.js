import "./bootstrap";
import Search from "./live-search";

// load search onlyu if yoy are logged in
if (document.querySelector(".header-search-icon")) {
    new Search();
}
