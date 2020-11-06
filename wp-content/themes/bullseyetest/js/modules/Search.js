import $ from 'jquery';

class LiveSearch {
  constructor() {
    this.addSearchHTML();
    this.openButton = $('.js-search-trigger');
    this.closeButton = $('.search-overlay__close');
    this.searchOverlay = $('.search-overlay');
    this.searchField = $('#search-term');
    this.resultsDiv = $('#search-overlay__results');
    this.isSpinnerVisible = false;
    this.isOverlayOpen = false;
    this.previousValue;
    this.typingTimer;
    this.searchResultsJSON;
    this.events();
  }

  events() {
    this.openButton.on('click', this.openOverlay.bind(this));
    this.closeButton.on('click', this.closeOverlay.bind(this));
    this.searchField.on('keyup', this.typingLogic.bind(this));
  }

  typingLogic() {
    if(this.previousValue != this.searchField.val()) {
      clearTimeout(this.typingTimer);
      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 800);
      } else {
          this.resultsDiv.html('');
          this.isSpinnerVisible = false;
      }
    }
    this.previousValue = this.searchField.val();
  }

  async getResults() {
    let search = this.searchField.val();
    let postBaseURL = `${bullseyeData.root_url}/wp-json/bullseye/v1/search?term=${search}`;
    let response;
    try {
      response = await fetch(postBaseURL);
    } catch (error) {
      console.log("error: " + error);
    }

    this.searchResultsJSON = await response.json();
    console.log(this.searchResultsJSON.generalInfo);
    this.isSpinnerVisible = false;
    this.resultsDiv.html('');
    let generalInfoHTML = this.searchResultsJSON.generalInfo.map((post, i) => `<li><a href="${post.link}">${post.title}</a>${post.postType == 'post' ? ` by ${post.authorName}`: ""}</li>`).join("");
    let professorsHTML = this.searchResultsJSON.professors.map((post, i) => `
    <li class="professor-card__list-item">
    <a class="professor-card" href="${post.link}">
      <img class="professor-card__image" src="${post.thumbnail}">
      <span class="professor-card__name">${post.title}</span>
    </a>
    </li>
    `).join("");
    let programsHTML = this.searchResultsJSON.programs.map((post, i) => `<li><a href="${post.link}">${post.title}</a></li>`).join("");
    let eventsHTML = this.searchResultsJSON.events.map((post, i) => `
    <div class="event-summary">
      <a class="event-summary__date t-center" href="${post.link}">
        <span class="event-summary__month">${post.month}</span>
        <span class="event-summary__day">${post.day}</span>
      </a>
      <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="${post.link}">${post.title}</a></h5>
        <p>${post.excerpt}<a href="${post.link}" class="nu gray"> Learn more</a></p>
      </div>
    </div>
    `).join("");
    let campusesHTML = this.searchResultsJSON.campuses.map((post, i) => `<li><a href="${post.link}">${post.title}</a></li>`).join("");

    if (!generalInfoHTML.length) {
      generalInfoHTML = `<li>There is no general information for this search.</li>`;
    }
    if (!professorsHTML.length) {
      professorsHTML = `<li>No professors match this search.</li>`;
    }
    if (!programsHTML.length) {
      programsHTML = `<li>No programs match this search. <a href="${bullseyeData.root_url}/programs">View all programs</a></li>`;
    }
    if (!eventsHTML.length) {
      eventsHTML = `<li>No events match this search. <a href="${bullseyeData.root_url}/events">View all events</a></li>`;
    }
    if (!campusesHTML.length) {
      campusesHTML = `<li>No campuses match this search. <a href="${bullseyeData.root_url}/campus">View all campuses</a></li>`;
    }

    this.resultsDiv.html(`
      <div class="row">
        <div class="one-third">
          <h2 class="search-overlay__section-title">General Information</h2>
          <ul class="link-list min-list">${generalInfoHTML}</ul>
        </div>
        <div class="one-third">
          <h2 class="search-overlay__section-title">Programs</h2>
          <ul class="link-list min-list">${programsHTML}</ul>
          <h2 class="search-overlay__section-title">Professors</h2>
          <ul class="profess-cards">${professorsHTML}</ul>
        </div>
        <div class="one-third">
          <h2 class="search-overlay__section-title">Events</h2>
          ${eventsHTML}
          <h2 class="search-overlay__section-title">Campuses</h2>
          <ul class="link-list min-list">${campusesHTML}</ul>
        </div>
      </div>`);
  }

  openOverlay() {
    this.searchOverlay.addClass("search-overlay--active");
    $('body').addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(()=> this.searchField.focus(), 301);
    this.isOverlayOpen = true;
    return false;
  }

  closeOverlay() {
    this.searchOverlay.removeClass("search-overlay--active");
    $('body').removeClass("body-no-scroll");
    this.isOverlayOpen = false;
  }

  addSearchHTML() {
    $('body').append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon " aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <i class="fa fa-window-close search-overlay__close " aria-hidden="true"></i>

          </div>
        </div>
        <div class="container">
            <div id="search-overlay__results">
            </div>
        </div>
      </div>`);
  }

}

export default LiveSearch;
