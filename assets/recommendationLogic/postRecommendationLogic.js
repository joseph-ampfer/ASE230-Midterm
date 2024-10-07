// function recommendPosts(major, userInterests, allPosts){
//     -find posts that are posted by the students with above major
//     -sort them according to the time they have been posted
//     -from the sorted list of posts find the posts that match the above interests with the keywords of the post
//     -count how many interests match with the keywords:
//     (suppose someone has interests as "JavaScript","HTML", "CSS", "React", "Python" listed in his interests
//     section in his profile and while searching suppose we found a post that has "HTML" as the keyword then we count as 1. If
//     we have found "HTML", "CSS", "React" we would have counted it as 3)
//     -Now the post that has the most matching keyword with the interest will be ranked top and similarly the least one will be ranked low
//     -Now several posts might be ranked with the same number. Suppose post 1 and post 6 could have same number of interests matching
//     then from them we will choose the one that has the most number of likes
//     -Now our model will return a list of posts with the latest posts matching the most interests from a particular major which has the most likes on top
//     and the other in descending order
//   }
//the major will be found when the user logs in and also the userInterests
//allPosts will be stored in our posts.json file which we will require later and import
function recommendPosts(major, allPosts, userInterests) {
  //Filter posts by students with the specified major
  let filteredPosts = allPosts.filter((post) => post.author.major === major);

  //Sort posts by time (assuming each post has a 'timestamp' field)
  filteredPosts.sort((a, b) => new Date(b.timestamp) - new Date(a.timestamp));

  //Find posts that match user interests with keywords of the post
  filteredPosts.forEach((post) => {
    // Assuming each post has a 'keywords' field, which is an array of keywords
    let matchingInterests = post.keywords.filter((keyword) =>
      userInterests.includes(keyword)
    );
    post.matchCount = matchingInterests.length; // Count how many interests match
  });

  //Sort posts based on the number of matching interests (highest matchCount first)
  filteredPosts.sort((a, b) => b.matchCount - a.matchCount);

  //Handle posts with the same number of matching interests
  filteredPosts = filteredPosts.sort((a, b) => {
    if (a.matchCount === b.matchCount) {
      // If match count is the same, sort by number of likes (assuming 'likes' field exists)
      return b.likes - a.likes;
    }
    return b.matchCount - a.matchCount; // Sort by match count first
  });

  //Return the list of posts in descending order (most likes with highest match count first)
  return filteredPosts;
}
