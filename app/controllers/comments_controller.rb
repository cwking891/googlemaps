class CommentsController < ApplicationController

  before_filter :authenticate, :only => [:new, :create]

# Add a new comment
  def new
    @comment = Comment.new #leave out id so form-for knows it is a new record
    @title = "Add Comment"
	@onload = "onload = setFocus('comment_subject')"
  end

  def create
    @comment  = current_user.comments.build(params[:comment])
    if @comment.save
      flash[:success] = "Thank you for your comment, #{current_user.name}."
      redirect_to root_path
    else
      @title = "Add Comment"
	  @onload = "onload = setFocus('comment_subject')"
      render 'new'
    end
  end

end
