	<!-- ============= POST PROJECT MODAL ================= -->
	<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Post Your Project</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body" style="text-align: left">

					<!-- FORM -->
					<div class="post-comment-form-cover">
						<form id="projectForm" class="comment-form" method="POST" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<label class="text-start" for="postTitle"><strong>Project Title</strong></label>
									<input required type="text" class="form-control" name="postTitle" placeholder="Project Title">
								</div>
								<div class="col-md-12 mb-5">
									<label class="text-start" for="postCategories"><strong>Project Categories</strong></label>
									<input required name='postCategories' class='w-100' placeholder='Choose categories for your project' value='' data-blacklist='badwords, asdf'>
								</div>
								<br /><br />
								<div class="col-md-12 mb-5">
									<label class="text-start" for="lookingFor"><strong>Looking For</strong></label>
									<input required name='lookingFor' class='w-100' placeholder='Who do you want to collaborate with?' value='' data-blacklist='badwords, asdf'>
								</div>
								<div class="col-md-12 mb-5">
									<label class="text-start" for="description"><strong>Project Description</strong></label>
									<textarea class="form-control" name="description" placeholder="Describe your project... your current progress... if you want collaboarators... etc."></textarea>
								</div>
								<div class="col-md-12">
									<label class="text-start" for="description"><strong>Project Image</strong></label>
									<input required type="file" class="form-control" name="postImage" accept="image/*">
								</div>

							</div>
						</form>
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					<button type="submit" form="projectForm" class="btn btn-primary">Post Project</button>
				</div>
			</div>
		</div>
	</div>