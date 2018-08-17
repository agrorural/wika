import { library, dom, config } from '@fortawesome/fontawesome-svg-core';
import { faBackspace, faCircleNotch, faSpinner, faSync, faArrowLeft, faArrowRight, faExclamationTriangle, faSearch, faQuestion, faSignInAlt, faSignOutAlt, faHome } from '@fortawesome/free-solid-svg-icons';
import { faBell, faBookmark, faCalendarAlt, faClock, faCommentAlt, faEdit, faEnvelope, faEye, faEyeSlash, faFile, faFileAlt, faFileExcel, faFilePdf, faFileWord, faImages, faLifeRing, faLightbulb, faListAlt, faNewspaper, faPlayCircle, faSave, faTrashAlt, faUser  } from '@fortawesome/free-regular-svg-icons';
// import { fab } from '@fortawesome/free-brands-svg-icons';

export default {
  init() {
    // JavaScript to be fired on all pages
    library.add(faBell, faBookmark, faCalendarAlt, faClock, faCommentAlt, faEdit, faEnvelope, faEye, faEyeSlash, faFile, faFileAlt, faFileExcel, faFilePdf, faFileWord, faImages, faLifeRing, faLightbulb, faListAlt, faNewspaper, faPlayCircle, faSave, faTrashAlt, faUser );
    library.add( faBackspace, faCircleNotch, faSpinner, faSync, faArrowLeft, faArrowRight, faExclamationTriangle, faSearch, faQuestion, faSignInAlt, faSignOutAlt, faHome );
    config.searchPseudoElements = true;
    // Replace any existing <i> tags with <svg> and set up a MutationObserver to
    // continue doing this as the DOM changes.
    dom.watch();
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
