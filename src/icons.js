import Vue from "vue";

import { library } from "@fortawesome/fontawesome-svg-core";
import { faUserSecret, faSignInAlt, faSignOutAlt, faPlug, faUser } from "@fortawesome/free-solid-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

library.add(faUserSecret, faSignInAlt, faSignOutAlt, faPlug, faUser);

Vue.component("fa-icon", FontAwesomeIcon);
