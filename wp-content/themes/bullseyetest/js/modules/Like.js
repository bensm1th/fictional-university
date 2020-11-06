import $ from 'jquery';
import axios from "axios"


class Like {
  constructor() {
    axios.defaults.headers.common["X-WP-Nonce"] = bullseyeData.nonce
    this.events();
  }

  events() {
    $('.like-box').on('click', this.ourClickDispatcher.bind(this))
  }

  ourClickDispatcher(e) {
    const currentLikeBox = $(e.target).closest(".like-box");

    if($(currentLikeBox).attr('data-exists') == 'yes') {
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }

  async createLike(currentLikeBox) {
    const data = {
      'professorId': currentLikeBox.data('professor')
    }
    const response = await axios.post(bullseyeData.root_url + "/wp-json/bullseye/v1/manageLike", data);
    currentLikeBox.attr('data-exists', 'yes');
    let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
    likeCount++;
    currentLikeBox.find('.like-count').html(likeCount);
    currentLikeBox.attr('data-like', response);
    console.log(response.data);
  }

  async deleteLike(currentLikeBox) {
    const deleteData = {
      'like': currentLikeBox.attr('data-like')
    }
    const response = await axios({
      url: bullseyeData.root_url + "/wp-json/bullseye/v1/manageLike",
      method: 'delete',
      data: deleteData
    });
    console.log(response);

    currentLikeBox.attr('data-exists', 'no');
    let likeCount = parseInt(currentLikeBox.find(".like-count").html(), 10);
    likeCount--;
    currentLikeBox.find('.like-count').html(likeCount);
    currentLikeBox.attr('data-like', "");
    console.log(response.data);
  }
}

export default Like;
