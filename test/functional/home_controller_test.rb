require 'test_helper'

class HomeControllerTest < ActionController::TestCase
  test "should get add" do
    get :add
    assert_response :success
  end

  test "should get login" do
    get :login
    assert_response :success
  end

  test "should get comment" do
    get :comment
    assert_response :success
  end

end
