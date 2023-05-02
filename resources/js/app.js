import "./bootstrap";
import Search from "./live-search";
import Chat from "./chat";
import Profile from "./profile";

// load search only if you are logged in

// click on search icon in header

if (document.querySelector(".header-search-icon")) {
    new Search();
}
// click on chat icon in header
if (document.querySelector(".header-chat-icon")) {
    new Chat();
}

if (document.querySelector(".profile-nav")) {
    new Profile();
}
